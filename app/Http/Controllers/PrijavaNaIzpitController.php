<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Izpit;
use App\Izpitni_rok;
use App\Izvedba_predmeta;
use App\Placljivs;
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
        date_default_timezone_set('Europe/Ljubljana');
        $vp = Student::where('email_studenta', Auth::user()->email)->pluck('vpisna_stevilka');
        $vpis = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', substr(date('Y'), 2, 2))->first();
        if(!$vpis){
            $vpis = Vpis::where('vpisna_stevilka', $vp)->orderBy('sifra_studijskega_leta', 'desc')->first();
            if(!$vpis)
                return redirect('home')->with('message', 'Nimate predmetov!');
        }
        $vpredmeti = Vpisan_predmet::where('vpisna_stevilka', $vp)->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->lists('sifra_predmeta');
        $roki = Izpitni_rok::where('sifra_studijskega_leta',  substr(date('Y'), 2, 2))->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->
            orderBy('datum', 'asc')->get();
        $rok = [];
        $predmeti = [];
        $profesorji = [];
        $stSkupajPrikaz = [];
        $stleto = [];
        $message = [];
        $mozno = [];
        $plakanje = [];
        for($i=0; $i<count($roki); $i++){
            $mozno[$i] = 1;
            $plakanje[$i] = 0;
            if(in_array($roki[$i]->sifra_predmeta, $vpredmeti)){
                $rok[$i][0] = $roki[$i];
                if($rok[$i][0]->opombe)
                    $message[$i] = $rok[$i][0]->opombe;
                else
                    $message[$i] = "";
            }else{
                $rok[$i][0] = [];
            }

            $iz = null;
            if(!empty($rok[$i][0])) {
                $iz = Izpit::where('vpisna_stevilka', $vp)->where('datum', $rok[$i][0]->datum)->where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->
                    where('sifra_predmeta', $roki[$i]->sifra_predmeta)->whereNull('cas_odjave')->first();
                $date = date( 'Y-m-d 01:00:00', strtotime($rok[$i][0]->datum));
            }

            if($iz){
                $rok[$i][1] = 1;
                if(date('Y-m-d H:m:s') > $date){
                    $message[$i] = "Rok za odjavo je potekel!";
                    $mozno[$i] = 0;
                }
            }else{
                $rok[$i][1] = 0;
            }

            $veke_prijaven = null;
            if(!empty($rok[$i][0]))
                $veke_prijaven = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $roki[$i]->sifra_predmeta)->where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->
                    where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('datum', $rok[$i][0]->datum)->whereNull('cas_odjave')->first();

            if($veke_prijaven){
                $rok[$i][2] = 1;
            }else{
                $rok[$i][2] = 0;
            }

            if(!empty($rok[$i][0])){
                $predmeti[$i] = Predmet::where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->pluck('naziv_predmeta');
                $profesorji[$i] = Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('priimek_profesorja').", ".
                    Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('ime_profesorja');

                $zad = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->
                    where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->whereNull('cas_odjave')->get();
                $iz2 = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->
                    where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->get();

                $pavzer = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->get();

                $stOdsteti = 0;
                $leta = Vpis::where('vpisna_stevilka', $vp)->where('sifra_vrste_vpisa', 2)->where('sifra_studijskega_programa', $rok[$i][0]->sifra_studijskega_programa)->pluck('sifra_studijskega_leta');
                if($pom=Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('sifra_studijskega_leta', $leta-1)->get()){
                    $stOdsteti = count($pom);
                }

                $stleto[$i] = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('ocena', '>', '0')->count();
                $stskupaj[$i] = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('ocena', '>', '0')->count();
                if($stOdsteti > 0)
                    $stSkupajPrikaz[$i] = $stskupaj[$i]." (-".$stOdsteti.")";
                else
                    $stSkupajPrikaz[$i] = $stskupaj[$i];

                if(!$rok[$i][2]) {
                    if ($stskupaj[$i] - $stOdsteti >= 3) {
                        $plakanje[$i] = 1;
                        $message[$i] = 'Za opravljanje izpita je potrebno plačati 80 EUR!';
                        if($plac=Placljivs::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->first()){
                            if(!$plac->placeno){
                                $message[$i] = 'Imate neporavnan račun.';
                                $mozno[$i] = 0;
                            }
                            $plakanje[$i] = 2;
                        }
                    }

                    if (count($pavzer) == 0) {
                        $plakanje[$i] = 1;
                        $message[$i] = 'Za opravljanje izpita je potrebno plačati 140 EUR! ';
                        if($plac=Placljivs::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->first()){
                            if(!$plac->placeno){
                                $message[$i] = 'Imate neporavnan račun.';
                                $mozno[$i] = 0;
                            }
                            $plakanje[$i] = 2;
                        }
                    }

                    if (count($iz2) > 0) {
                        for ($k = 0; $k < count($iz2); $k++){
                            if ($iz2[$k]->cas_odjave == null) {
                                if ($iz2[$k]->ocena == null) {
                                    if ($iz2[$k]->datum > date('Y-m-d')) {
                                        $message[$i] = 'Prijava na izpit za ta predmet že obstaja! ';
                                        $mozno[$i] = 0;
                                    } else {
                                        $message[$i] = 'Za prejšnji rok še ni bila zaključena ocena! ';
                                        $mozno[$i] = 0;
                                    }
                                }else{
                                    if (count($zad) > 0 && $zad[0]->datum < date('Y-m-d')) {
                                        $limit = date('Y-m-d', strtotime($zad[0]->datum.' +7 day'));
                                        if ($limit > date('Y-m-d')) {
                                            $message[$i] = 'Ni preteklo dovolj dni od zadnjega polaganja! ';
                                            $mozno[$i] = 0;
                                        }
                                    }
                                }

                                if ($iz2[$k]->ocena > 5) {
                                    $message[$i] = 'Opravljen izpit!';
                                    $mozno[$i] = 0;
                                }
                            }
                        }
                    }

                    if (date('Y-m-d H:m:s') > $date) {
                        $message[$i] = 'Rok za prijavo je potekel! ';
                        $mozno[$i] = 0;
                    }

                    if ($stleto[$i] >= 3) {
                        $message[$i] = 'Prekoračili ste število polaganj v tekočem študijskem letu! ';
                        $mozno[$i] = 0;
                    }

                    if ($stskupaj[$i] - $stOdsteti >= 6) {
                        $message[$i] = 'Prekoračili ste celotno število polaganj! ';
                        $mozno[$i] = 0;
                    }

                }else{
                    if($veke_prijaven->ocena != null) {
                        $message[$i] = "Ocenjeno!";
                        if($veke_prijaven->ocena > 5)
                            $message[$i] = "Opravljen izpit!";

                        $mozno[$i] = 0;
                    }
                }
            }
        }
        return view('prijavanaizpit', ['rok' => $rok, 'predmeti' => $predmeti, 'profesorji' => $profesorji, 'vpisna' => $vp, 'msg' => $message, 'stleto'=>$stleto,
            'stskupaj'=>$stSkupajPrikaz, 'mozno' => $mozno, 'plakanje'=>$plakanje]);
    }

    public function Prijava($vse){
        date_default_timezone_set('Europe/Ljubljana');
        $type = Auth::user()->type;
        $vp = explode(" ", $vse)[0];
        $slet = explode(" ", $vse)[1];
        $spred = explode(" ", $vse)[2];
        $sprof = explode(" ", $vse)[3];
        $sstdprog = explode(" ", $vse)[4];
        $sstdleta = explode(" ", $vse)[5];
        $datum = explode(" ", $vse)[6];
        $plakanje = explode(" ", $vse)[7];

        if($plakanje == 1) {
            $placl = new Placljivs();
            $placl->vpisna_stevilka = $vp;
            $placl->sifra_predmeta = $spred;
            $placl->placeno = 0;
            $placl->save();
        }elseif($plakanje == 2){
            Placljivs::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->update(['placeno' => 0]);
        }

        if(count(Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->where('sifra_profesorja', $sprof)->where('sifra_studijskega_leta', $sstdleta)->where('datum', $datum)->get()) != 0) {
            Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->where('sifra_studijskega_leta', $sstdleta)->
                where('sifra_profesorja', $sprof)->where('datum', $datum)->update(['email_odjavitelja' => null, 'cas_odjave' => null]);
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

        if($type == 2)
            return $this->RokiR($vp);
        else
            return $this->Roki();
    }

    public function Odjava($vse){
        date_default_timezone_set('Europe/Ljubljana');
        $type = Auth::user()->type;
        $email = Auth::user()->email;
        $vp = explode(" ", $vse)[0];
        $spred = explode(" ", $vse)[2];
        $sprof = explode(" ", $vse)[3];
        $datum = explode(" ", $vse)[6];

        Placljivs::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $spred)->delete();
        $iz = Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $spred)->first();

        if($iz){
            Izpit::where('vpisna_stevilka', $vp)->whereNull('ocena')->where('sifra_predmeta', $spred)->where('sifra_profesorja', $sprof)->
                where('datum', $datum)->update(['email_odjavitelja' => $email, 'cas_odjave' => date('H:m:s')]);
        }

        if($type == 2)
            return $this->RokiR($vp);
        else
            return $this->Roki();
    }

    public function RokiR($vp){
        date_default_timezone_set('Europe/Ljubljana');
        $vpis = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', substr(date('Y'), 2, 2))->first();
        if(!$vpis){
            $vpis = Vpis::where('vpisna_stevilka', $vp)->orderBy('sifra_studijskega_leta', 'desc')->first();
            if(!$vpis)
                return redirect('home')->with('message', 'Nimate predmetov!');
        }
        $vpredmeti = Vpisan_predmet::where('vpisna_stevilka', $vp)->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->lists('sifra_predmeta');
        $roki = Izpitni_rok::where('sifra_studijskega_leta',  substr(date('Y'), 2, 2))->where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->
        orderBy('datum', 'asc')->get();
        $rok = [];
        $predmeti = [];
        $profesorji = [];
        $stSkupajPrikaz = [];
        $stleto = [];
        $message = [];
        $mozno = [];
        $plakanje = [];
        for($i=0; $i<count($roki); $i++){
            $mozno[$i] = 1;
            $plakanje[$i] = 0;
            if(in_array($roki[$i]->sifra_predmeta, $vpredmeti)){
                $rok[$i][0] = $roki[$i];
                if($rok[$i][0]->opombe)
                    $message[$i] = $rok[$i][0]->opombe;
                else
                    $message[$i] = "";
            }else{
                $rok[$i][0] = [];
            }

            $iz = null;
            if(!empty($rok[$i][0])) {
                $iz = Izpit::where('vpisna_stevilka', $vp)->where('datum', $rok[$i][0]->datum)->where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->
                    where('sifra_predmeta', $roki[$i]->sifra_predmeta)->whereNull('cas_odjave')->first();
                $date = date( 'Y-m-d 01:00:00', strtotime($rok[$i][0]->datum));
            }

            if($iz){
                $rok[$i][1] = 1;
                if(date('Y-m-d H:m:s') > $date){
                    $message[$i] = "Rok za odjavo je potekel!";
                }
            }else{
                $rok[$i][1] = 0;
            }

            $veke_prijaven = null;
            if(!empty($rok[$i][0]))
                $veke_prijaven = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $roki[$i]->sifra_predmeta)->where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->
                    where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('datum', $rok[$i][0]->datum)->whereNull('cas_odjave')->first();

            if($veke_prijaven){
                $rok[$i][2] = 1;
            }else{
                $rok[$i][2] = 0;
            }

            if(!empty($rok[$i][0])){
                $predmeti[$i] = Predmet::where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->pluck('naziv_predmeta');
                $profesorji[$i] = Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('priimek_profesorja').", ".
                    Profesor::where('sifra_profesorja', $rok[$i][0]->sifra_profesorja)->pluck('ime_profesorja');

                $zad = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->
                where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->whereNull('cas_odjave')->get();
                $iz2 = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->
                where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->get();

                $pavzer = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->get();

                $stOdsteti = 0;
                $leta = Vpis::where('vpisna_stevilka', $vp)->where('sifra_vrste_vpisa', 2)->where('sifra_studijskega_programa', $rok[$i][0]->sifra_studijskega_programa)->pluck('sifra_studijskega_leta');
                if($pom=Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('sifra_studijskega_leta', $leta-1)->get()){
                    $stOdsteti = count($pom);
                }

                $stleto[$i] = Izpit::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $rok[$i][0]->sifra_studijskega_leta)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('ocena', '>', '0')->count();
                $stskupaj[$i] = Izpit::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->where('ocena', '>', '0')->count();
                if($stOdsteti > 0)
                    $stSkupajPrikaz[$i] = $stskupaj[$i]." (-".$stOdsteti.")";
                else
                    $stSkupajPrikaz[$i] = $stskupaj[$i];

                if(!$rok[$i][2]) {
                    if ($stskupaj[$i] - $stOdsteti >= 3) {
                        $plakanje[$i] = 1;
                        $message[$i] = 'Za opravljanje izpita je potrebno plačati 80 EUR!';
                        if($plac=Placljivs::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->first()){
                            if(!$plac->placeno){
                                $message[$i] = 'Imate neporavnan račun.';
                            }
                            $plakanje[$i] = 2;
                        }
                    }

                    if (count($pavzer) == 0) {
                        $plakanje[$i] = 1;
                        $message[$i] = 'Za opravljanje izpita je potrebno plačati 140 EUR! ';
                        if($plac=Placljivs::where('vpisna_stevilka', $vp)->where('sifra_predmeta', $rok[$i][0]->sifra_predmeta)->first()){
                            if(!$plac->placeno){
                                $message[$i] = 'Imate neporavnan račun.';
                            }
                            $plakanje[$i] = 2;
                        }
                    }

                    if (count($iz2) > 0) {
                        for ($k = 0; $k < count($iz2); $k++){
                            if ($iz2[$k]->cas_odjave == null) {
                                if ($iz2[$k]->ocena == null) {
                                    if ($iz2[$k]->datum > date('Y-m-d')) {
                                        $message[$i] = 'Prijava na izpit za ta predmet že obstaja! ';
                                    } else {
                                        $message[$i] = 'Za prejšnji rok še ni bila zaključena ocena! ';
                                    }
                                }else{
                                    if (count($zad) > 0 && $zad[0]->datum < date('Y-m-d')) {
                                        $limit = date('Y-m-d', strtotime($zad[0]->datum.' +7 day'));
                                        if ($limit > date('Y-m-d')) {
                                            $message[$i] = 'Ni preteklo dovolj dni od zadnjega polaganja! ';
                                        }
                                    }
                                }

                                if ($iz2[$k]->ocena > 5) {
                                    $message[$i] = 'Opravljen izpit!';
                                }
                            }
                        }
                    }

                    if (date('Y-m-d H:m:s') > $date) {
                        $message[$i] = 'Rok za prijavo je potekel! ';
                    }

                    if ($stleto[$i] >= 3) {
                        $message[$i] = 'Prekoračili ste število polaganj v tekočem študijskem letu! ';
                    }

                    if ($stskupaj[$i] - $stOdsteti >= 6) {
                        $message[$i] = 'Prekoračili ste celotno število polaganj! ';
                    }

                }else{
                    if($veke_prijaven->ocena != null) {
                        $message[$i] = "Ocenjeno!";
                        if($veke_prijaven->ocena > 5)
                            $message[$i] = "Opravljen izpit!";
                        $mozno[$i] = 0;
                    }

                    if($veke_prijaven->tocke_izpita != null){
                        $message[$i] = "Vpisana ocena izpita!";
                        $mozno[$i] = 0;
                    }
                }
            }
        }

        return view('prijavanaizpit', ['rok' => $rok, 'predmeti' => $predmeti, 'profesorji' => $profesorji, 'vpisna' => $vp, 'msg' => $message, 'stleto'=>$stleto,
            'stskupaj'=>$stSkupajPrikaz, 'mozno' => $mozno, 'plakanje'=>$plakanje]);
    }

}
