<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Izpit;
use App\Letnik;
use App\Nacin_studija;
use App\Predmet;
use App\Profesor;
use App\Student;
use App\Studijski_program;
use App\Studijsko_leto;
use App\Vpis;
use App\Vpisan_predmet;
use App\Vrsta_vpisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class KartotecniListController extends Controller {

    public function gumb(){
        if(Input::get('vsa')){
            return $this->vrniVsa();
        } elseif(Input::get('zadnja')){
            return $this->vrniZadnja();
        }
    }

    public function vrniVsa(){
        $email = Auth::user()->email;
        $student = Student::where('email_studenta', $email)->get()[0];
        $vpisna = $student->vpisna_stevilka;
        $name = $student->priimek_studenta.", ".$student->ime_studenta." (".$vpisna.")";
        $active = [];
        $active[0] = "active";
        $active[1] = "";
        $programi = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->lists('sifra_studijskega_programa');
        $res2 = array();
        foreach($programi as $key=>$val) {
            $res2[$val] = true;
        }
        $programi = array_keys($res2);
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]." " .Studijski_program::where('sifra_studijskega_programa', $programi[$i])->pluck('naziv_studijskega_programa');
        }
        array_unshift($studijski_programi, "");

        if(Input::get('studiskiprogram') != 0){
            $studijski_program[0] = $programi[Input::get('studiskiprogram')-1];
        }else{
            $studijski_program = $programi;
        }

        $povp = [];
        $skupnare = [];
        $skupkt = [];
        $izpiti = [];
        $heading = [];
        $sh = [];

        for($s=0; $s<count($studijski_program); $s++) {
            $leta = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_programa', $studijski_program[$s])->lists('sifra_studijskega_leta');
            $res2 = array();
            foreach ($leta as $key => $val) {
                $res2[$val] = true;
            }
            $leta = array_keys($res2);
            $stleto = [];
            $stskupaj = [];
            for ($i = 0; $i < count($leta); $i++) {
                $sh[$s][$i] = 0;
                $povp[$s][$i] = 0;
                $skupkt[$s][$i] = 0;
                $st = 0;
                $stleto[$s][$i] = [];
                $he = Vpis::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->get()[0];
                $heading[$s][$i][0] = Studijsko_leto::where('sifra_studijskega_leta', $leta[$i])->pluck('stevilka_studijskega_leta');
                $heading[$s][$i][1] = Letnik::where('sifra_letnika', $he->sifra_letnika)->pluck('stevilka_letnika');
                $heading[$s][$i][2] = Vrsta_vpisa::where('sifra_vrste_vpisa', $he->sifra_vrste_vpisa)->pluck('opis_vrste_vpisa');
                $heading[$s][$i][3] = Nacin_studija::where('sifra_nacina_studija', $he->sifra_nacina_studija)->pluck('opis_nacina_studija');
                $predmeti = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->get();
                for ($j = 0; $j < count($predmeti); $j++) {

                    if(count($izi = Izpit::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->where('sifra_predmeta',$predmeti[$j]->sifra_predmeta)->where('ocena', '>', 0)->get())) {
                        for($z=0; $z<count($izi); $z++) {
                            $izpiti[$s][$i][$sh[$s][$i]][0] = $predmeti[$j]->sifra_predmeta;
                            $izpiti[$s][$i][$sh[$s][$i]][1] = Predmet::where('sifra_predmeta', $izpiti[$s][$i][$sh[$s][$i]][0])->pluck('naziv_predmeta');
                            $izpiti[$s][$i][$sh[$s][$i]][3] = Letnik::where('sifra_letnika', $predmeti[$j]->sifra_letnika)->pluck('stevilka_letnika') . ". letnik";
                            $izpiti[$s][$i][$sh[$s][$i]][7] = Predmet::where('sifra_predmeta', $izpiti[$s][$i][$sh[$s][$i]][0])->pluck('stevilo_KT');
                            $izpiti[$s][$i][$sh[$s][$i]][4] = date('d.m.Y', strtotime($izi[$z]->datum));
                            $izpiti[$s][$i][$sh[$s][$i]][2] = Profesor::where('sifra_profesorja', $izi[$z]->sifra_profesorja)->
                                pluck('priimek_profesorja') . ", " . Profesor::where('sifra_profesorja', $izi[$z]->sifra_profesorja)->pluck('ime_profesorja');

                            if (array_key_exists($izpiti[$s][$i][$sh[$s][$i]][0], $stskupaj)) {
                                $stskupaj[$izpiti[$s][$i][$sh[$s][$i]][0]] += 1;
                            } else {
                                $stskupaj[$izpiti[$s][$i][$sh[$s][$i]][0]] = 1;
                            }

                            if (array_key_exists($izpiti[$s][$i][$sh[$s][$i]][0], $stleto[$s][$i])) {
                                $stleto[$s][$i][$izpiti[$s][$i][$sh[$s][$i]][0]] += 1;
                            } else {
                                $stleto[$s][$i][$izpiti[$s][$i][$sh[$s][$i]][0]] = 1;
                            }

                            $izpiti[$s][$i][$sh[$s][$i]][5] = $stleto[$s][$i][$izpiti[$s][$i][$sh[$s][$i]][0]];
                            if ($he->sifra_vrste_vpisa == 2 && $stleto[$s][$i - 1][$izpiti[$s][$i][$sh[$s][$i]][0]] != null) {
                                $izpiti[$s][$i][$sh[$s][$i]][6] = $stskupaj[$izpiti[$s][$i][$sh[$s][$i]][0]] . " (-" . $stleto[$s][$i - 1][$izpiti[$s][$i][$sh[$s][$i]][0]] . ")";
                            } else {
                                $izpiti[$s][$i][$sh[$s][$i]][6] = $stskupaj[$izpiti[$s][$i][$sh[$s][$i]][0]];
                            }

                            $izpiti[$s][$i][$sh[$s][$i]][8] = $izi[$z]->ocena;
                            if ($izi[$z]->ocena > 5) {
                                $skupkt[$s][$i] += $izpiti[$s][$i][$sh[$s][$i]][7];
                                $povp[$s][$i] += $izi[$z]->ocena;
                                $st++;
                            }
                            $sh[$s][$i]++;
                        }
                    }else{
                        $izpiti[$s][$i][$sh[$s][$i]][0] = $predmeti[$j]->sifra_predmeta;
                        $izpiti[$s][$i][$sh[$s][$i]][1] = Predmet::where('sifra_predmeta', $izpiti[$s][$i][$sh[$s][$i]][0])->pluck('naziv_predmeta');
                        $izpiti[$s][$i][$sh[$s][$i]][3] = Letnik::where('sifra_letnika', $predmeti[$j]->sifra_letnika)->pluck('stevilka_letnika') . ". letnik";
                        $izpiti[$s][$i][$sh[$s][$i]][7] = Predmet::where('sifra_predmeta', $izpiti[$s][$i][$sh[$s][$i]][0])->pluck('stevilo_KT');
                        $izpiti[$s][$i][$j][2] = "";
                        $izpiti[$s][$i][$j][4] = "";
                        $izpiti[$s][$i][$j][5] = "";
                        $izpiti[$s][$i][$j][6] = "";
                        $izpiti[$s][$i][$j][8] = "";
                        $sh[$s][$i]++;
                    }
                }
                $skupnare[$s][$i] = $st;
                if($st != 0)
                    $povp[$s][$i] = round($povp[$s][$i]/$st, 3);
                else
                    $povp[$s][$i] = 0;
            }
        }

        return view('kartotecnilist',['name'=> $name, 'active'=>$active, 'studijski_programi'=>$studijski_programi, 'skupkt'=>$skupkt,
                'studijski_program'=>$studijski_program, 'heading'=>$heading, 'izpiti'=>$izpiti, 'povp'=>$povp,'skupnare'=>$skupnare, 'stpredmetov'=>$sh]);
    }

    public function vrniZadnja(){
        $email = Auth::user()->email;
        $student = Student::where('email_studenta', $email)->get()[0];
        $vpisna = $student->vpisna_stevilka;
        $name = $student->priimek_studenta.", ".$student->ime_studenta." (".$vpisna.")";
        $active = [];
        $active[0] = "";
        $active[1] = "active";
        $programi = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->lists('sifra_studijskega_programa');
        $res2 = array();
        foreach($programi as $key=>$val) {
            $res2[$val] = true;
        }
        $programi = array_keys($res2);
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]." " .Studijski_program::where('sifra_studijskega_programa', $programi[$i])->pluck('naziv_studijskega_programa');
        }
        array_unshift($studijski_programi, "");

        if(Input::get('studiskiprogram') != 0){
            $studijski_program[0] = $programi[Input::get('studiskiprogram')-1];
        }else{
            $studijski_program = $programi;
        }

        $povp = [];
        $skupnare = [];
        $skupkt = [];
        $izpiti = [];
        $heading = [];

        for($s=0; $s<count($studijski_program); $s++) {
            $leta = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_programa', $studijski_program[$s])->lists('sifra_studijskega_leta');
            $res2 = array();
            foreach ($leta as $key => $val) {
                $res2[$val] = true;
            }
            $leta = array_keys($res2);

            $stleto = [];
            $stskupaj = [];

            for ($i = 0; $i < count($leta); $i++) {
                $povp[$s][$i] = 0;
                $skupkt[$s][$i] = 0;
                $st = 0;
                $stleto[$s][$i] = [];
                $he = Vpis::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->get()[0];
                $heading[$s][$i][0] = Studijsko_leto::where('sifra_studijskega_leta', $leta[$i])->pluck('stevilka_studijskega_leta');
                $heading[$s][$i][1] = Letnik::where('sifra_letnika', $he->sifra_letnika)->pluck('stevilka_letnika');
                $heading[$s][$i][2] = Vrsta_vpisa::where('sifra_vrste_vpisa', $he->sifra_vrste_vpisa)->pluck('opis_vrste_vpisa');
                $heading[$s][$i][3] = Nacin_studija::where('sifra_nacina_studija', $he->sifra_nacina_studija)->pluck('opis_nacina_studija');
                $predmets = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->get();

                for ($j = 0; $j < count($predmets); $j++) {
                    $necinit = 1;
                    $predmet = Izpit::where('vpisna_stevilka', $vpisna)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $leta[$i])->where('sifra_predmeta', $predmets[$j])->
                    orderBy('datum', 'desc')->first();
                    if($predmet == null) {
                        $predmet = $predmets[$j];
                        $necinit = 0;
                    }
                    $kaunt = count(Izpit::where('vpisna_stevilka', $vpisna)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $leta[$i])->
                    where('sifra_predmeta', $predmets[$j])->get());
                    $izpiti[$s][$i][$j][0] = $predmet->sifra_predmeta;
                    $izpiti[$s][$i][$j][7] = Predmet::where('sifra_predmeta', $izpiti[$s][$i][$j][0])->pluck('stevilo_KT');
                    $izpiti[$s][$i][$j][1] = Predmet::where('sifra_predmeta', $izpiti[$s][$i][$j][0])->pluck('naziv_predmeta');
                    $izpiti[$s][$i][$j][3] = Letnik::where('sifra_letnika', $predmet->sifra_letnika)->pluck('stevilka_letnika') . ". letnik";

                    if($necinit) {
                        $izpiti[$s][$i][$j][2] = Profesor::where('sifra_profesorja', $predmet->sifra_profesorja)->pluck('priimek_profesorja') . ", " . Profesor::where('sifra_profesorja', $predmet->sifra_profesorja)->pluck('ime_profesorja');
                        $izpiti[$s][$i][$j][4] = date('d.m.Y', strtotime($predmet->datum));
                        if (array_key_exists($izpiti[$s][$i][$j][0], $stskupaj)) {
                            $stskupaj[$izpiti[$s][$i][$j][0]] += $kaunt;
                        } else {
                            $stskupaj[$izpiti[$s][$i][$j][0]] = $kaunt;
                        }

                        if (array_key_exists($izpiti[$s][$i][$j][0], $stleto[$s][$i])) {
                            $stleto[$s][$i][$izpiti[$s][$i][$j][0]] += $kaunt;
                        } else {
                            $stleto[$s][$i][$izpiti[$s][$i][$j][0]] = $kaunt;
                        }

                        $izpiti[$s][$i][$j][5] = $stleto[$s][$i][$izpiti[$s][$i][$j][0]];

                        if ($he->sifra_vrste_vpisa == 2 && $stleto[$s][$i - 1][$izpiti[$s][$i][$j][0]] != null) {
                            $izpiti[$s][$i][$j][6] = $stskupaj[$izpiti[$s][$i][$j][0]] . " (-" . $stleto[$s][$i - 1][$izpiti[$s][$i][$j][0]] . ")";
                        } else {
                            $izpiti[$s][$i][$j][6] = $stskupaj[$izpiti[$s][$i][$j][0]];
                        }

                        $izpiti[$s][$i][$j][8] = $predmet->ocena;
                        if ($predmet->ocena > 5) {
                            $skupkt[$s][$i] += $izpiti[$s][$i][$j][7];
                            $povp[$s][$i] += $predmet->ocena;
                            $st++;
                        }
                    }else{
                        $izpiti[$s][$i][$j][2] = "";
                        $izpiti[$s][$i][$j][4] = "";
                        $izpiti[$s][$i][$j][5] = "";
                        $izpiti[$s][$i][$j][6] = "";
                        $izpiti[$s][$i][$j][8] = "";
                    }
                }
                $skupnare[$s][$i] = $st;
                if($st != 0)
                    $povp[$s][$i] = round($povp[$s][$i]/$st, 3);
                else
                    $povp[$s][$i] = 0;
            }
        }

        return view('kartotecnilist',['name'=> $name, 'active'=>$active, 'studijski_programi'=>$studijski_programi, 'skupkt'=>$skupkt,
            'studijski_program'=>$studijski_program, 'heading'=>$heading, 'izpiti'=>$izpiti, 'povp'=>$povp,'skupnare'=>$skupnare]);
    }
}
