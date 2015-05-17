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
        $vpredmeti = Vpisan_predmet::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $vpis->sifra_studijskega_leta)->
            where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->where('sifra_letnika', $vpis->sifra_letnika)->lists('sifra_predmeta');
        $rok = [];
        $predmeti = [];
        $profesorji = [];
        for($i=0; $i<count($vpredmeti); $i++){
            $rok[$i] = Izpitni_rok::where('sifra_studijskega_leta', $vpis->sifra_studijskega_leta)->
            where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->where('sifra_letnika', $vpis->sifra_letnika)->
                where('sifra_predmeta', $vpredmeti[$i])->get();

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

        $date =  date( 'Y-m-d', strtotime( $datum. ' -3 day' ));

        $stleto = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $sstdleta)->where('sifra_predmeta', $spred)->count();
        $stskupaj = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->count();

        $zad = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->get();
        $limit = date( 'Y-m-d', strtotime( $zad[0]->datum. ' +14 day' ));

        if($stskupaj > 6){
            return Redirect::back()->with('message', 'Prekoračili ste celotno število polaganj!');;
        }

        if($stleto > 3){
            return Redirect::back()->with('message', 'Prekoračili ste število polaganj v tekočem študijskem letu!');
        }

        if($limit > date('Y-m-d')){
            return Redirect::back()->with('message', 'Ni preteklo dovolj dni od zadnjega polaganja!');
        }

        if(date('Y-m-d') > $date) {
            return Redirect::back()->with('message', 'Rok za prijavo je potekel!');
        }

        $izp = new Izpit();
        $izp->vpisna_stevilka = $vp;
        $izp->sifra_letnika = $slet;
        $izp->sifra_predmeta = $spred;
        $izp->sifra_profesorja = $sprof;
        $izp->sifra_studijskega_programa = $sstdprog;
        $izp->sifra_studijskega_leta = $sstdleta;
        $izp->datum = $datum;
        $izp->ocena = 0;
        $izp->save();
    }

}
