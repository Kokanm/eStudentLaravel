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
        $vpredmeti = Vpisan_predmet::where('vpisna_stevilka', $vp)->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->
            where('sifra_letnika', $vpis->sifra_letnika)->lists('sifra_predmeta');
        $rok = [];
        $predmeti = [];
        $profesorji = [];
        for($i=0; $i<count($vpredmeti); $i++){
            $rok[$i][0] = Izpitni_rok::where('sifra_studijskega_leta',  substr(date('Y'), 2, 2))->
            where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->where('sifra_letnika', $vpis->sifra_letnika)->
                where('sifra_predmeta', $vpredmeti[$i])->first();

            $iz = Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $vpredmeti[$i])->whereNull('cas_odjave')->first();
            if($iz){
                $rok[$i][1] = 1;
            }else{
                $rok[$i][1] = 0;
            }

            if(!empty($rok[$i][0])){
                $predmeti[$i] = Predmet::where('sifra_predmeta', $vpredmeti[$i])->pluck('naziv_predmeta');
                $profesorji[$i] = Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('priimek_profesorja').", ".
                    Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('ime_profesorja');
            }
        }

        return view('prijavanaizpit', ['rok' => $rok, 'predmeti' => $predmeti, 'profesorji' => $profesorji, 'vpisna' => $vp]);
    }

    public function Prijava($vse){
        $vp = explode(" ", $vse)[0];
        $slet = explode(" ", $vse)[1];
        $spred = explode(" ", $vse)[2];
        $sprof = explode(" ", $vse)[3];
        $sstdprog = explode(" ", $vse)[4];
        $sstdleta = explode(" ", $vse)[5];
        $datum = explode(" ", $vse)[6];

        $date =  date( 'Y-m-d', strtotime( $datum. ' -1 day' ));

        $zad = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->get();
        $iz = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $sstdleta)->where('sifra_predmeta', $spred)->get();

        $pavzer = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', '<', $sstdleta)->get();
        $ponavljalec = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $sstdleta)->where('sifra_studijskega_programa', $sstdprog)->
            where('sifra_vrste_vpisa', 2)->get();

        if(count($ponavljalec) == 1){
            Izpit::where('vpisna_stevilka', $vp)->where('ocena', '<', '6')->update(['ocena'=>0]);;
        }

        $stleto = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $sstdleta)->where('sifra_predmeta', $spred)->where('ocena', '>', '0')->count();
        $stskupaj = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->where('ocena', '>', '0')->count();


        if(count($iz) > 0){
            if($iz[0]->datum > date('Y-m-d')){
                return Redirect::back()->with('message', 'Prijava na izpit za ta predmet že obstaja!');
            }

            if($iz[0]->ocena == null){
                return Redirect::back()->with('message', 'Za prejšnji rok še ni bila zaključena ocena!');
            }
        }

        if(count($pavzer) == 1){
            return Redirect::back()->with('message', 'Morate plačat za ta rok (pavzer)!');
        }

        if($stskupaj > 3){
            return Redirect::back()->with('message', 'Morate plačat za ta rok!');
        }

        if($stskupaj > 6){
            return Redirect::back()->with('message', 'Prekoračili ste celotno število polaganj!');
        }

        if($stleto > 3){
            return Redirect::back()->with('message', 'Prekoračili ste število polaganj v tekočem študijskem letu!');
        }

        if(count($zad) > 0) {
            $limit = date('Y-m-d', strtotime($zad[0]->datum . ' +14 day'));
            if($limit > date('Y-m-d')){
                return Redirect::back()->with('message', 'Ni preteklo dovolj dni od zadnjega polaganja!');
            }
        }

        if(date('Y-m-d') > $date) {
            return Redirect::back()->with('message', 'Rok za prijavo je potekel!');
        }

        if($iz){
            echo "AAAA";
        }else{
            echo "BBBB";
        }
        asd;
        if($iz->cas_odjave == null) {
            $izp = new Izpit();
            $izp->vpisna_stevilka = $vp;
            $izp->sifra_letnika = $slet;
            $izp->sifra_predmeta = $spred;
            $izp->sifra_profesorja = $sprof;
            $izp->sifra_studijskega_programa = $sstdprog;
            $izp->sifra_studijskega_leta = $sstdleta;
            $izp->datum = $datum;
            $izp->save();
        }else{
            Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->update(['email_odjavitelja' => null, 'cas_odjave' => null, 'datum' => $datum]);;
        }

        return $this->Roki();
    }

    public function Odjava($vse){
        $email = Auth::user()->email;
        $vp = explode(" ", $vse)[0];
        $spred = explode(" ", $vse)[2];
        $datum = explode(" ", $vse)[6];

        $date =  date( 'Y-m-d', strtotime( $datum. ' -1 day' ));


        $iz = Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $spred)->first();

        if($iz){
            if(date('Y-m-d') > $date){
                return Redirect::back()->with('message', 'Rok za odjavo je potekel!');
            }
            Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $spred)->update(['email_odjavitelja' => $email, 'cas_odjave' => date('H:m:s')]);
        }

        return $this->Roki();
    }

}
