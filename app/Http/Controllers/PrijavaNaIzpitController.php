<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Izpit;
use App\Izpitni_rok;
use App\Izvedba_predmeta;
use App\Predmet;
use App\Profesor;
use App\Student;
use App\Vpis;
use App\Vpisan_predmet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PrijavaNaIzpitController extends Controller {

	public function Roki(){
        $vp = Student::where('email_studenta', Auth::user()->email)->pluck('vpisna_stevilka');
        $vpis = Vpis::where('vpisna_stevilka', $vp)->first();
        if(!$vpis){
            return redirect('home')->with('message', 'Nimate predmete!');
        }
        $vpredmeti = Vpisan_predmet::where('vpisna_stevilka', $vp)->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->
            where('sifra_letnika', $vpis->sifra_letnika)->lists('sifra_predmeta');
        $roki = Izpitni_rok::where('sifra_studijskega_leta',  substr(date('Y'), 2, 2))->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->
            where('sifra_letnika', $vpis->sifra_letnika)->get();
        $rok = [];
        $predmeti = [];
        $profesorji = [];
        $message = [];
        $tip = [];
        for($i=0; $i<count($roki); $i++){
            $message[$i] = "";
            if(in_array($roki[$i]->sifra_predmeta, $vpredmeti)){
                $rok[$i][0] = $roki[$i];
            }else{
                $rok[$i][0] = [];
            }

            $iz = Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $vpredmeti[$i])->whereNull('cas_odjave')->first();
            if($iz){
                $rok[$i][1] = 1;
            }else{
                $rok[$i][1] = 0;
            }

            if(!empty($rok[$i][0])){
                $predmeti[$i] = Predmet::where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->pluck('naziv_predmeta');
                $profesorji[$i] = Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('priimek_profesorja').", ".
                    Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('ime_profesorja');

                $date = date( 'Y-m-d 01:00:00', strtotime($rok[$i][0]->datum));

                $zad = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->whereNull('cas_odjave')->get();
                $iz2 = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->whereNull('cas_odjave')->get();

                $pavzer = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', '<', $rok[$i][0]->sifra_studijskega_leta)->get();
                $ponavljalec = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('sifra_studijskega_programa', $rok[$i][0]->sifra_studijskega_programa)->
                where('sifra_vrste_vpisa', 2)->get();

                if(count($ponavljalec) == 1){
                    Izpit::where('vpisna_stevilka', $vp)->where('ocena', '<', '6')->update(['ocena'=>0]);;
                }

                $stleto = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('ocena', '>', '0')->count();
                $stskupaj = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('ocena', '>', '0')->count();

                if(date('Y-m-d H:m:s') > $date) {
                    $message[$i] = 'Rok za prijavo je potekel! ';
                }

                if(count($zad) > 0) {
                    $limit = date('Y-m-d', strtotime($zad[0]->datum . ' +14 day'));
                    if($limit > date('Y-m-d')){
                        $message[$i] = 'Ni preteklo dovolj dni od zadnjega polaganja! ';
                    }
                }

                if($stleto > 3){
                    $message[$i] = 'Prekoračili ste število polaganj v tekočem študijskem letu! ';
                }

                if($stskupaj > 6){
                    $message[$i] = 'Prekoračili ste celotno število polaganj! ';
                }

                if($stskupaj > 3){
                    $message[$i] = 'Morate plačat za ta rok! ';
                }

                if(count($pavzer) == 1){
                    $message[$i] = 'Morate plačat za ta rok (pavzer)! ';
                }


                if(count($iz2) > 0){
                    if($iz2[0]->datum > date('Y-m-d')){
                        $message[$i] = 'Prijava na izpit za ta predmet že obstaja! ';
                    }

                    if($iz2[0]->ocena == null){
                        $message[$i] = 'Za prejšnji rok še ni bila zaključena ocena! ';
                    }
                }

                $tip[$i] = 0;
                if(count($iz2) > 0 && $iz2[0]->cas_odjave == null){
                    $tip[$i] = 1;
                }
            }

        }
        return view('prijavanaizpit', ['rok' => $rok, 'predmeti' => $predmeti, 'profesorji' => $profesorji, 'vpisna' => $vp, 'msg' => $message, 'tip' => $tip]);
    }

    public function Prijava($vse){
        date_default_timezone_set('Europe/Ljubljana');
        $vp = explode(" ", $vse)[0];
        $slet = explode(" ", $vse)[1];
        $spred = explode(" ", $vse)[2];
        $sprof = explode(" ", $vse)[3];
        $sstdprog = explode(" ", $vse)[4];
        $sstdleta = explode(" ", $vse)[5];
        $datum = explode(" ", $vse)[6];
        $tip = explode(" ", $vse)[7];

        if($tip == 1) {
            Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->update(['email_odjavitelja' => null, 'cas_odjave' => null, 'datum' => $datum]);;
        }else{
            $izp = new Izpit();
            $izp->vpisna_stevilka = $vp;
            $izp->sifra_letnika = $slet;
            $izp->sifra_predmeta = $spred;
            $izp->sifra_profesorja = $sprof;
            $izp->sifra_studijskega_programa = $sstdprog;
            $izp->sifra_studijskega_leta = $sstdleta;
            $izp->datum = $datum;
            $izp->save();
        }

        return $this->Roki();
    }

    public function Odjava($vse){
        date_default_timezone_set('Europe/Ljubljana');
        $email = Auth::user()->email;
        $vp = explode(" ", $vse)[0];
        $spred = explode(" ", $vse)[2];
        $datum = explode(" ", $vse)[6];

        $date = date( 'Y-m-d 01:00:00', strtotime($datum));

        $iz = Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $spred)->first();

        if($iz){
            if(date('Y-m-d H:m:s') > $date){
                return Redirect::back()->with('message', 'Rok za odjavo je potekel!');
            }
            Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $spred)->update(['email_odjavitelja' => $email, 'cas_odjave' => date('H:m:s')]);
        }

        return $this->Roki();
    }

}
