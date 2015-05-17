<?php namespace App\Http\Controllers;

use App\Kandidat;
use App\Letnik;
use App\Nacin_studija;
use App\Oblika_studija;
use App\Predmet_studijskega_programa;
use App\Predmet;
use App\Profesor;
use App\Sestavni_del_predmetnika;
use App\Student;
use App\Drzava;
use App\Posta;
use App\Izvedba_predmeta;
use App\Obcina;
use App\Studijsko_leto;
use App\Vpis;
use App\Vpisan_predmet;
use App\Vrsta_studija;
use App\Vrsta_vpisa;
use App\Studijski_program;
use App\Http\Requests;
use App\Zeton;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class VpisniListController extends Controller {

	public function vpisi(Requests\VpisniListRequest $request){
        if ($user = Auth::user()) {
            if ($user->type == 0) {
                $programi = Studijski_program::get();
                $studijski_programi = [];
                for ($i = 0; $i < count($programi); $i++) {
                    $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
                }

                $drzave = Drzava::lists('naziv_drzave');
                $obcine = Obcina::lists('naziv_obcine');
                $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');
                $oblik = Oblika_studija::lists('opis_oblike_studija');
                $nacin = Nacin_studija::lists('opis_nacina_studija');

                $posti = Posta::get();
                $poste = [];
                for ($i = 0; $i < count($posti); $i++) {
                    $poste[$i] = $posti[$i]->postna_stevilka . " " . $posti[$i]->naziv_poste;
                }
                array_unshift($poste, "");

                $studija = Vrsta_studija::get();
                $vrste_studija = [];
                for ($i = 0; $i < count($studija); $i++) {
                    $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija . " " . $studija[$i]->opis_vrste_studija;
                }

                $list = $request->all();

                $errors = [];
                if($list['drzavarojstva'] == 0){
                    $errors[] = "Selektirajte drazavo rojstva!";
                }

                if($list['krajrojstva'] == 0){
                    $errors[] = "Selektirajte občino rojstva!";
                }

                if($list['drzavljanstvo'] == 0){
                    $errors[] = "Selektirajte drzavljanstvo!";
                }

                if($list['postastalno'] == 0){
                    $errors[] = "Selektirajte pošto stalnega bivališča!";
                }

                if($list['studiskiprogram'] == 0){
                    $errors[] = "Selektirajte študujski program!";
                }

                if($list['vrstastudija'] == 0){
                    $errors[] = "Selektirajte vrsto študija!";
                }

                if($list['vrstavpisa'] == 0){
                    $errors[] = "Selektirajte vrsto vpisa!";
                }

                if($list['letnikdodatno'] == 0){
                    $errors[] = "Selektirajte letnika!";
                }

                if($list['nacin'] == 0){
                    $errors[] = "Selektirajte načina študija!";
                }

                if($list['oblika'] == 0){
                    $errors[] = "Selektirajte obliko študija!";
                }

                if(!empty($errors)){
                    return Redirect::back()->withInput()->withErrors($errors);
                }

                $emso = $list["emso"];
                $datum = $list["datumrojstva"];

                $std = new Student;
                $std->vpisna_stevilka = $list["vstevilka"];
                $std->ime_studenta = ucfirst(explode(" ", $list["imepriimek"])[0]);
                $std->priimek_studenta = ucfirst(explode(" ", $list["imepriimek"])[1]);
                $std->datum_rojstva = date("Y-m-d", strtotime($datum));
                $std->sifra_obcine_rojstva = Obcina::where('naziv_obcine', $obcine[$list['krajrojstva'] - 1])->pluck('sifra_obcine');
                $std->sifra_drzave_rojstva = Drzava::where('naziv_drzave', $drzave[$list['drzavarojstva'] - 1])->pluck('sifra_drzave');
                $std->sifra_drzave_drzavljanstva = Drzava::where('naziv_drzave', $drzave[$list['drzavljanstvo'] - 1])->pluck('sifra_drzave');
                $std->spol = strtoupper($list["spol"])[0];

                if (Drzava::where('sifra_drzave', $std->sifra_drzave_rojstva)->pluck('naziv_drzave') == "Slovenija" &&
                    $std->sifra_obcine_rojstva == 999)
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država rojstva!");
                elseif(Drzava::where('sifra_drzave', $std->sifra_drzave_rojstva)->pluck('naziv_drzave') != "Slovenija" && $std->sifra_obcine_rojstva != 999)
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država rojstva!");


                if (substr($datum, 0, 2) == substr($emso, 0, 2) && substr($datum, 3, 2) == substr($emso, 2, 2)
                    && substr($datum, 7, 3) == substr($emso, 4, 3) && substr($emso, 7, 2) > 49 && substr($emso, 7, 2) < 60
                ) {
                    if ($std->spol == "M" && substr($emso, 9, 3) >= 0 && substr($emso, 9, 3) < 500) {
                        $kontrolna = $emso[0] * 7 + $emso[1] * 6 + $emso[2] * 5 + $emso[3] * 4 +
                            $emso[4] * 3 + $emso[5] * 2 + $emso[6] * 7 + $emso[7] * 6 + $emso[8] * 5 +
                            $emso[9] * 4 + $emso[10] * 3 + $emso[11] * 2;

                        $ost = 11 - $kontrolna % 11;
                        if ($emso[12] == $ost) {
                            $std->emso = $emso;
                        } else {
                            return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (kontrolna)");
                        }

                    } elseif (($std->spol == "Z" || $std->spol == "Ž") && substr($emso, 9, 3) >= 500 && substr($emso, 9, 3) < 1000) {
                        $kontrolna = $emso[0] * 7 + $emso[1] * 6 + $emso[2] * 5 + $emso[3] * 4 +
                            $emso[4] * 3 + $emso[5] * 2 + $emso[6] * 7 + $emso[7] * 6 + $emso[8] * 5 +
                            $emso[9] * 4 + $emso[10] * 3 + $emso[11] * 2;
                        $ost = 11 - $kontrolna % 11;
                        if ($emso[12] == $ost) {
                            $std->emso = $emso;
                        } else {
                            return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (kontrolna)");
                        }
                    } else {
                        return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (spol)");
                    }
                } else {
                    return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (datum)");
                }

                $std->davcna_stevilka = $list["davcna"];
                $std->email_studenta = $list["email"];
                $std->prenosni_telefon = $list["gsm"];

                $std->postna_stevilka_stalno = explode(" ", $poste[$list['postastalno']])[0];
                $std->sifra_obcine_stalno = Obcina::where('naziv_obcine', $obcine[$list['obcinastalno'] - 1])->pluck('sifra_obcine');
                $std->sifra_drzave_stalno = Drzava::where('naziv_drzave', $drzave[$list['drzavastalno'] - 1])->pluck('sifra_drzave');
                $std->naslov_stalno = $list["naslovstalno"];
                if (Drzava::where('sifra_drzave', $std->sifra_drzave_stalno)->pluck('naziv_drzave') == "Slovenija" &&
                    !$std->sifra_obcine_stalno == 999) {
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država stalnega prebivališča!");
                }elseif(Drzava::where('sifra_drzave', $std->sifra_drzave_stalno)->pluck('naziv_drzave') != "Slovenija" && $std->sifra_obcine_stalno != 999) {
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država stalnega prebivališča!");
                }

                if(!empty($list['obcinazacasno'])){
                    $std->postna_stevilka_zacasno = explode(" ", $poste[$list['postazacasno']])[0];
                    $std->sifra_obcine_zacasno = Obcina::where('naziv_obcine', $obcine[$list['obcinazacasno'] - 1])->pluck('sifra_obcine');
                    $std->sifra_drzave_zacasno = Drzava::where('naziv_drzave', $drzave[$list['drzavazacasno'] - 1])->pluck('sifra_drzave');
                    $std->naslov_zacasno = $list["naslovzacasno"];
                    if (Drzava::where('sifra_drzave', $std->sifra_drzave_zacasno)->pluck('naziv_drzave') == "Slovenija" &&
                        $std->sifra_obcine_zacasno == 999)
                        return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država začasnega prebivališča!");
                    elseif(Drzava::where('sifra_drzave', $std->sifra_drzave_zacasno)->pluck('naziv_drzave') != "Slovenija" && $std->sifra_obcine_zacasnoo != 999)
                        return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država začasnega prebivališča!");
                }

                if($list['vrocanje'] == 'vstalno'){
                    $std->naslov_vrocanja = $list["naslovstalno"];
                }elseif($list['vrocanje'] == 'vzacasno'){
                    $std->naslov_vrocanja = $list["naslovzacasno"];
                }

                $std->save();

                $vp = new Vpis;
                $vp->sifra_studijskega_programa = explode(" ", $studijski_programi[$list['studiskiprogram'] - 1])[0];
                $vp->sifra_vrste_studija = explode(" ", $vrste_studija[$list['vrstastudija'] - 1])[0];
                $vp->sifra_vrste_vpisa = Vrsta_vpisa::where('opis_vrste_vpisa', $vrste_vpisa[$list['vrstavpisa'] - 1])->pluck('sifra_vrste_vpisa');

                if($vp->sifra_vrste_studija == 16204 && !($vp->sifra_studijskega_programa == 1000425 || $vp->sifra_studijskega_programa == 1000475
                        || $vp->sifra_studijskega_programa == 1001001 || $vp->sifra_studijskega_programa == 1000469))
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija študijski program + vrsta študija UNI");
                elseif($vp->sifra_vrste_studija == 16203 && !($vp->sifra_studijskega_programa == 1000470 || $vp->sifra_studijskega_programa == 1000477))
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija študijski program + vrsta študija VS");
                elseif($vp->sifra_vrste_studija == 17003 && !($vp->sifra_studijskega_programa == 1000471 || $vp->sifra_studijskega_programa == 1000934))
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija študijski program + vrsta študija MAG");

                $vp->sifra_letnika = $list['letnikdodatno'];

                if($vp->sifra_vrste_vpisa == 1 && $vp->sifra_letnika != 1)
                    return Redirect::back()->withInput()->withErrors("Napačѝna kombinacija vrsta vpisa + letnik");
                elseif($vp->sifra_vrste_vpisa == 2 &&  $vp->sifra_letnika == 1)
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija vrsta vpisa + letnik");
                elseif($vp->sifra_vrste_vpisa == 3 &&  $vp->sifra_letnika == 1)
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija vrsta vpisa + letnik");


                $vp->sifra_nacina_studija = Nacin_studija::where('opis_nacina_studija', $nacin[$list['nacin'] - 1])->pluck('sifra_nacina_studija');
                $vp->sifra_oblike_studija = Oblika_studija::where('opis_oblike_studija', $oblik[$list['oblika'] - 1])->pluck('sifra_oblike_studija');
                $vp->sifra_studijskega_leta = Studijsko_leto::where('stevilka_studijskega_leta', date('Y') . "/" . (date('Y') + 1))->pluck('sifra_studijskega_leta');
                $vp->vpisna_stevilka = $list["vstevilka"];

                if (array_key_exists('zavod', $list)) {
                    $vp->zavod = $list['zavod'];
                }

                if (array_key_exists('krajizvajanja', $list)) {
                    $vp->kraj_izvajanja = $list['krajizvajanja'];
                }
                $vp->save();

                $obvezni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', NULL)->lists('sifra_predmeta');
                $obvezni_predmeti = [];
                $sum = 0;
                for ($i = 0; $i < count($obvezni); $i++) {
                    $obvezni_predmeti[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                    pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                    pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $obvezni[$i])->
                    pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT')];
                    $vpisi = new Vpisan_predmet();
                    $vpisi->vpisna_stevilka = $list["vstevilka"];
                    $vpisi->sifra_studijskega_leta = $vp->sifra_studijskega_leta;
                    $vpisi->sifra_predmeta = $obvezni[$i];
                    $vpisi->sifra_studijskega_programa = $vp->sifra_studijskega_programa;
                    $vpisi->sifra_letnika = $vp->sifra_letnika;
                    $vpisi->sifra_studijskega_leta_izvedbe_predmeta = $vp->sifra_studijskega_leta;
                    $vpisi->save();
                    $sum += Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT');
                }

                $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', '7')->lists('sifra_predmeta');
                $prosti = [];
                for ($i = 0; $i < count($prosto_izbirni); $i++) {
                    $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                        pluck('stevilo_KT') . " KT";
                }
                if (!empty($prosti))
                    array_unshift($prosti, "");


                $strokovno_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
                $strokovni = [];
                for ($i = 0; $i < count($strokovno_izbirni); $i++) {
                    $strokovni[$i] = Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->
                        pluck('stevilo_KT') . " KT";
                }
                if (!empty($strokovni))
                    array_unshift($strokovni, "");

                $pomos = $vp->vpisna_stevilka . $vp->sifra_studijskega_leta . $vp->sifra_studijskega_programa . $vp->sifra_letnika;


                return view('predmeti', ['studijski_program' => $studijski_programi[$list['studiskiprogram'] - 1], 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'vpisna' => $pomos, 'tip' => 0, 'tips' => 0]);

            }elseif($user->type == 1){
                $programi = Studijski_program::get();
                $studijski_programi = [];
                for ($i=0; $i < count($programi); $i++){
                    $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa." ".$programi[$i]->naziv_studijskega_programa;
                }

                $drzave = Drzava::lists('naziv_drzave');
                $obcine = Obcina::lists('naziv_obcine');
                $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');
                $oblik = Oblika_studija::lists('opis_oblike_studija');
                $nacin = Nacin_studija::lists('opis_nacina_studija');

                $posti = Posta::get();
                $poste = [];
                for ($i = 0; $i < count($posti); $i++) {
                    $poste[$i] = $posti[$i]->postna_stevilka . " " . $posti[$i]->naziv_poste;
                }
                array_unshift($poste, "");


                $studija = Vrsta_studija::get();
                $vrste_studija = [];
                for ($i=0; $i < count($studija); $i++){
                    $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija." ".$studija[$i]->opis_vrste_studija;
                }

                $list = $request->all();

                $errors = [];
                if($list['drzavarojstva'] == 0){
                    $errors[] = "Selektirajte drazavo rojstva!";
                }

                if($list['krajrojstva'] == 0){
                    $errors[] = "Selektirajte občino rojstva!";
                }

                if($list['drzavljanstvo'] == 0){
                    $errors[] = "Selektirajte drzavljanstvo!";
                }

                if($list['postastalno'] == 0){
                    $errors[] = "Selektirajte pošto stalnega bivališča!";
                }

                if($list['studiskiprogram'] == 0){
                    $errors[] = "Selektirajte študujski program!";
                }

                if($list['vrstastudija'] == 0){
                    $errors[] = "Selektirajte vrsto študija!";
                }

                if($list['vrstavpisa'] == 0){
                    $errors[] = "Selektirajte vrsto vpisa!";
                }

                if($list['letnikdodatno'] == 0){
                    $errors[] = "Selektirajte letnika!";
                }

                if($list['nacin'] == 0){
                    $errors[] = "Selektirajte načina študija!";
                }

                if($list['oblika'] == 0){
                    $errors[] = "Selektirajte obliko študija!";
                }

                if(!empty($errors)){
                    return Redirect::back()->withInput()->withErrors($errors);
                }

                $zet = Zeton::where('vpisna_stevilka', $list['vstevilka'])->where('sifra_studijskega_leta', substr(date('Y'), 2,2))->get()[0];
                $emso = $list["emso"];
                $datum = $list["datumrojstva"];

                $std = Student::where('vpisna_stevilka', $list["vstevilka"])->get()[0];
                $std->ime_studenta = ucfirst(explode(" ", $list["imepriimek"])[0]);
                $std->priimek_studenta = ucfirst(explode(" ", $list["imepriimek"])[1]);
                $std->datum_rojstva = date("Y-m-d", strtotime($datum));
                $std->sifra_obcine_rojstva = Obcina::where('naziv_obcine', $obcine[$list['krajrojstva'] - 1])->pluck('sifra_obcine');
                $std->sifra_drzave_rojstva = Drzava::where('naziv_drzave', $drzave[$list['drzavarojstva']-1])->pluck('sifra_drzave');
                $std->sifra_drzave_drzavljanstva = Drzava::where('naziv_drzave', $drzave[$list['drzavljanstvo']-1])->pluck('sifra_drzave');
                $std->spol = strtoupper($list["spol"])[0];

                if (Drzava::where('sifra_drzave', $std->sifra_drzave_rojstva)->pluck('naziv_drzave') == "Slovenija" &&
                    $std->sifra_obcine_rojstva == 999)
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država rojstva!");
                elseif(Drzava::where('sifra_drzave', $std->sifra_drzave_rojstva)->pluck('naziv_drzave') != "Slovenija" && $std->sifra_obcine_rojstva != 999)
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država rojstva!");


                if(substr($datum, 0, 2) == substr($emso, 0, 2) && substr($datum, 3, 2) == substr($emso, 2, 2)
                    && substr($datum, 7, 3) == substr($emso, 4, 3) && substr($emso, 7, 2) > 49 && substr($emso, 7, 2) < 60)
                {
                    if($std->spol == "M" && substr($emso, 9, 3) >= 0 && substr($emso, 9, 3) < 500)
                    {
                        $kontrolna = $emso[0]*7 + $emso[1]*6 + $emso[2]*5 + $emso[3]*4 +
                            $emso[4]*3 + $emso[5]*2 + $emso[6]*7 + $emso[7]*6 + $emso[8]*5 +
                            $emso[9]*4 + $emso[10]*3 + $emso[11]*2;

                        $ost = 11 - $kontrolna % 11;
                        if($emso[12] == $ost){
                            $std->emso = $emso;
                        }else{
                            return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (kontrolna)");
                        }

                    }elseif(($std->spol == "Z" || $std->spol == "Ž") && substr($emso, 9, 3) >= 500 && substr($emso, 9, 3) < 1000){
                        $kontrolna = $emso[0]*7 + $emso[1]*6 + $emso[2]*5 + $emso[3]*4 +
                            $emso[4]*3 + $emso[5]*2 + $emso[6]*7 + $emso[7]*6 + $emso[8]*5 +
                            $emso[9]*4 + $emso[10]*3 + $emso[11]*2;
                        $ost = 11 - $kontrolna % 11;
                        if($emso[12] == $ost){
                            $std->emso = $emso;
                        }else{
                            return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (kontrolna)");
                        }
                    }else{
                        return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (spol)");
                    }
                }else{
                    return Redirect::back()->withInput()->withErrors("Napačno EMŠO!!! (datum)");
                }

                $std->davcna_stevilka = $list["davcna"];
                $std->email_studenta = $list["email"];
                $std->prenosni_telefon = $list["gsm"];

                $std->postna_stevilka_stalno = explode(" ", $poste[$list['postastalno']])[0];
                $std->sifra_obcine_stalno = Obcina::where('naziv_obcine', $obcine[$list['obcinastalno'] - 1])->pluck('sifra_obcine');
                $std->sifra_drzave_stalno = Drzava::where('naziv_drzave', $drzave[$list['drzavastalno'] - 1])->pluck('sifra_drzave');
                $std->naslov_stalno = $list["naslovstalno"];
                if (Drzava::where('sifra_drzave', $std->sifra_drzave_stalno)->pluck('naziv_drzave') == "Slovenija" &&
                    !$std->sifra_obcine_stalno == 999) {
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država stalnega prebivališča!");
                }elseif(Drzava::where('sifra_drzave', $std->sifra_drzave_stalno)->pluck('naziv_drzave') != "Slovenija" && $std->sifra_obcine_stalno != 999) {
                    return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država stalnega prebivališča!");
                }

                if(!empty($list['obcinazacasno'])){
                    $std->postna_stevilka_zacasno = explode(" ", $poste[$list['postazacasno']])[0];
                    $std->sifra_obcine_zacasno = Obcina::where('naziv_obcine', $obcine[$list['obcinazacasno'] - 1])->pluck('sifra_obcine');
                    $std->sifra_drzave_zacasno = Drzava::where('naziv_drzave', $drzave[$list['drzavazacasno'] - 1])->pluck('sifra_drzave');
                    $std->naslov_zacasno = $list["naslovzacasno"];
                    if (Drzava::where('sifra_drzave', $std->sifra_drzave_zacasno)->pluck('naziv_drzave') == "Slovenija" &&
                        $std->sifra_obcine_zacasno == 999)
                        return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država začasnega prebivališča!");
                    elseif(Drzava::where('sifra_drzave', $std->sifra_drzave_zacasno)->pluck('naziv_drzave') != "Slovenija" && $std->sifra_obcine_zacasnoo != 999)
                        return Redirect::back()->withInput()->withErrors("Izbrana napačna občina ali država začasnega prebivališča!");
                }

                if($list['vrocanje'] == 'vstalno'){
                    $std->naslov_vrocanja = $list["naslovstalno"];
                }elseif($list['vrocanje'] == 'vzacasno'){
                    $std->naslov_vrocanja = $list["naslovzacasno"];
                }

                $std->save();

                $vp = Vpis::where('vpisna_stevilka', $list["vstevilka"])->get()[0];

                if($list['studiskiprogram'] == 0)
                    return Redirect::back()->withInput()->withErrors("Izberite študijski program!");
                else
                    $vp->sifra_studijskega_programa = explode(" ", $studijski_programi[$list['studiskiprogram']-1])[0];

                if($zet->sifra_studijskega_programa != $vp->sifra_studijskega_programa){
                    return Redirect::back()->withInput()->withErrors("Nimate dovolenje za ta študijski program!");
                }

                $vp->sifra_vrste_studija = explode(" ", $vrste_studija[$list['vrstastudija']-1])[0];
                $vp->sifra_vrste_vpisa = Vrsta_vpisa::where('opis_vrste_vpisa', $vrste_vpisa[$list['vrstavpisa']-1])->pluck('sifra_vrste_vpisa');
                if($zet->sifra_vrste_vpisa != $vp->sifra_vrste_vpisa){
                    return Redirect::back()->withInput()->withErrors("Nimate dovolenje za to vrsto vpisa!");
                }

                if($vp->sifra_vrste_studija == 16204 && !($vp->sifra_studijskega_programa == 1000425 || $vp->sifra_studijskega_programa == 1000475
                        || $vp->sifra_studijskega_programa == 1001001 || $vp->sifra_studijskega_programa == 1000469))
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija študijski program + vrsta študija UNI");
                elseif($vp->sifra_vrste_studija == 16203 && !($vp->sifra_studijskega_programa == 1000470 || $vp->sifra_studijskega_programa == 1000477))
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija študijski program + vrsta študija VS");
                elseif($vp->sifra_vrste_studija == 17003 && !($vp->sifra_studijskega_programa == 1000471 || $vp->sifra_studijskega_programa == 1000934))
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija študijski program + vrsta študija MAG");


                $vp->sifra_nacina_studija = Nacin_studija::where('opis_nacina_studija', $nacin[$list['nacin']-1])->pluck('sifra_nacina_studija');
                if($zet->sifra_nacina_studija != $vp->sifra_nacina_studija){
                    return Redirect::back()->withInput()->withErrors("Nimate dovolenje za ta nacin študija!");
                }

                $vp->sifra_oblike_studija = Oblika_studija::where('opis_oblike_studija', $oblik[$list['oblika']-1])->pluck('sifra_oblike_studija');
                if($zet->sifra_oblike_studija != $vp->sifra_oblike_studija){
                    return Redirect::back()->withInput()->withErrors("Nimate dovolenje za to obliko študija!");
                }

                $vp->sifra_studijskega_leta = substr(date('Y'),2,2);

                if(array_key_exists('zavod', $list)){
                    $vp->zavod = $list['zavod'];
                }

                if(array_key_exists('krajizvajanja', $list)){
                    $vp->kraj_izvajanja = $list['krajizvajanja'];
                }

                $let = Vpis::where('vpisna_stevilka', $list['vstevilka'])->pluck('sifra_letnika');

                if ($list['letnikdodatno'][0] != 'd') {
                    if($list['letnikdodatno'] == 1)
                        $vp->sifra_letnika = $let;
                    else
                        $vp->sifra_letnika = $let+1;
                }else
                    $vp->sifra_letnika = 7;

                if($zet->sifra_letnika != $vp->sifra_letnika){
                    return Redirect::back()->withInput()->withErrors("Nimate dovolenje za vpis v ta letnik!");
                }

                if($vp->sifra_vrste_vpisa == 1 && $vp->sifra_letnika != $let+1)
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija vrsta vpisa + letnik");
                elseif($vp->sifra_vrste_vpisa == 2 &&  $vp->sifra_letnika != $let)
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija vrsta vpisa + letnik");
                elseif($vp->sifra_vrste_vpisa == 3 &&  $vp->sifra_letnika != $let)
                    return Redirect::back()->withInput()->withErrors("Napačna kombinacija vrsta vpisa + letnik");

                if($vp->sifra_vrste_vpisa == 2 &&  $vp->sifra_letnika == $let)
                    Vpisan_predmet::where('vpisna_stevilka', $list['vstevilka'])->where('sifra_letnika', $vp->sifra_letnika)->delete();

                $vp->save();

                $obvezni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', NULL)->lists('sifra_predmeta');
                $obvezni_predmeti = [];
                $sum = 0;
                for($i=0; $i<count($obvezni); $i++){
                    $obvezni_predmeti[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                    pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$obvezni[$i])->
                    pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $obvezni[$i])->
                    pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT')];
                    $vpisi = new Vpisan_predmet();
                    $vpisi->vpisna_stevilka = $list["vstevilka"];
                    $vpisi->sifra_studijskega_leta = $vp->sifra_studijskega_leta;
                    $vpisi->sifra_predmeta = $obvezni[$i];
                    $vpisi->sifra_studijskega_programa = $vp->sifra_studijskega_programa;
                    $vpisi->sifra_letnika = $vp->sifra_letnika;
                    $vpisi->sifra_studijskega_leta_izvedbe_predmeta = $vp->sifra_studijskega_leta;
                    $vpisi->save();
                    $sum += Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT');
                }

                $strokovno_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
                $strokovni = [];
                for ($i=0; $i < count($strokovno_izbirni); $i++){
                    $strokovni[$i] = Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->
                        pluck('stevilo_KT')." KT";
                }
                if(!empty($strokovni))
                    array_unshift($strokovni, "");


                $moduli = [];
                if($vp->sifra_letnika == 3) {
                    $moduli = Sestavni_del_predmetnika::where('sifra_sestavnega_dela', '!=', '6')->where('sifra_sestavnega_dela', '!=', '7')->lists('opis_sestavnega_dela');
                    array_unshift($moduli, "");
                }

                $pomos = $vp->vpisna_stevilka.$vp->sifra_studijskega_leta.$vp->sifra_studijskega_programa.$vp->sifra_letnika.$zet->prosta_izbira_predmetov;
                $modularni = [];

                if($vp->sifra_letnika == 2) {
                    $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                    where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', '7')->orWhere('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
                    $prosti = [];
                    for ($i = 0; $i < count($prosto_izbirni); $i++) {
                        $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                            pluck('stevilo_KT') . " KT";
                    }
                }else{
                    $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
                    where('sifra_letnika', $vp->sifra_letnika)->lists('sifra_predmeta');
                    $prosti = [];
                    for ($i = 0; $i < count($prosto_izbirni); $i++) {
                        $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                            pluck('stevilo_KT') . " KT";
                    }
                }
                if (!empty($prosti))
                    array_unshift($prosti, "");

                if($vp->sifra_letnika==3 && $zet->prosta_izbira_predmetov == 1){
                    $modpredmeti = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->where('sifra_letnika', $vp->sifra_letnika)->
                    where('sifra_sestavnega_dela','!=', '6')->where('sifra_sestavnega_dela', '!=', '7')->whereNotNull('sifra_sestavnega_dela')->lists('sifra_predmeta');

                    for($i=0; $i<count($modpredmeti); $i++) {
                        $modularni[$i] = Predmet::where('sifra_predmeta', $modpredmeti[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $modpredmeti[$i])->
                            pluck('stevilo_KT')." KT";
                    }
                    if(!empty($modularni))
                        array_unshift($modularni, "");
                }

                return view('predmeti', ['studijski_program' => $studijski_programi[$list['studiskiprogram']-1], 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $pomos, 'modularni'=>$modularni, 'tips'=>0]);
            }
        }

    }


    public function select()
    {
        if ($user = Auth::user()) {
            if ($user->type == 0) {
                $kandidat = Kandidat::where('email_kandidata', $user->email)->get();
                $zac = "63" . substr(date('Y'), 2, 2);
                $st = count(Student::where('vpisna_stevilka', 'LIKE', $zac . '%')->get());
                if (floor($st / 10) == 0)
                    $vp = $zac . "000" . $st;
                elseif (floor($st / 100) == 0)
                    $vp = $zac . "00" . $st;
                elseif (floor($st / 1000) == 0)
                    $vp = $zac . "0" . $st;
                else
                    $vp = $zac . "" . $st;

                $kandidat->vpisna_stevilka = $vp;
                $programi = Studijski_program::get();
                $studijski_programi = [];
                for ($i = 0; $i < count($programi); $i++) {
                    $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
                }
                array_unshift($studijski_programi, "");

                $stdpro = array_search($kandidat[0]->sifra_studijskega_programa." ".Studijski_program::where('sifra_studijskega_programa',
                        $kandidat[0]->sifra_studijskega_programa)->pluck('naziv_studijskega_programa'), $studijski_programi);

                $drzave = Drzava::lists('naziv_drzave');
                array_unshift($drzave, "");
                asort($drzave);

                $obcine = Obcina::lists('naziv_obcine');
                array_unshift($obcine, "");
                asort($obcine);

                $letnik = Letnik::lists('stevilka_letnika');
                array_unshift($letnik, "");
                $posti = Posta::get();
                $poste = [];
                for ($i = 0; $i < count($posti); $i++) {
                    $poste[$i] = $posti[$i]->naziv_poste. " " .$posti[$i]->postna_stevilka;
                }
                array_unshift($poste, "");
                asort($poste);

                $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');
                array_pop($vrste_vpisa);
                array_unshift($vrste_vpisa, "");

                $oblik = Oblika_studija::lists('opis_oblike_studija');
                array_unshift($oblik, "");
                $nacin = Nacin_studija::lists('opis_nacina_studija');
                array_unshift($nacin, "");

                $studija = Vrsta_studija::get();
                $vrste_studija = [];
                for ($i = 0; $i < count($studija); $i++) {
                    $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija . " " . $studija[$i]->opis_vrste_studija;
                }
                array_unshift($vrste_studija, "");

                return view('vpisnilist', ['studijski_programi' => $studijski_programi, 'letnik' => array_slice($letnik, 0, 2),
                    'vrste_vpisa' => $vrste_vpisa, 'vrste_studija' => $vrste_studija, 'drzave' => $drzave, 'obcine' => $obcine,
                    'oblik' => $oblik, 'nacin' => $nacin, 'kand' => $kandidat[0], 'vp' => $vp, 'tip'=>0, 'poste'=>$poste, 'stdpro'=>$stdpro]);
            } elseif ($user->type == 1){
                $student = Student::where('email_studenta', $user->email)->get();
                $vpis = Vpis::where('vpisna_stevilka', $student[0]->vpisna_stevilka)->get()[0];

                $zet = Zeton::where('vpisna_stevilka', $student[0]->vpisna_stevilka)->where('sifra_studijskega_leta', substr(date('Y'), 2,2))->
                    where('sifra_studijskega_programa', $vpis->sifra_studijskega_programa)->get();

                if(!empty($zet[0])) {
                    if ($zet[0]->zeton_porabljen)
                        return redirect('home')->with('message', 'Porabljen žeton!');
                }else {
                    return redirect('home')->with('message', 'Nimate dovolenje za vpis!');
                }

                Vpis::where('vpisna_stevilka', $student[0]->vpisna_stevilka)->update(['vpis_potrjen'=>0]);

                $programi = Studijski_program::get();
                $studijski_programi = [];
                for ($i = 0; $i < count($programi); $i++) {
                    $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
                }
                array_unshift($studijski_programi, "");

                $drzave = Drzava::lists('naziv_drzave');
                array_unshift($drzave, "");
                asort($drzave);

                $obcine = Obcina::lists('naziv_obcine');
                array_unshift($obcine, "");
                asort($obcine);

                $letnik = array_slice(Letnik::lists('stevilka_letnika'), $vpis->sifra_letnika-1 ,2);
                array_unshift($letnik, "");

                if($letnik[1] == $zet[0]->sifra_letnika)
                    $let = 1;
                else
                    $let = 2;

                $posti = Posta::get();
                $poste = [];
                for ($i = 0; $i < count($posti); $i++) {
                    $poste[$i] = $posti[$i]->naziv_poste. " " .$posti[$i]->postna_stevilka;
                }
                array_unshift($poste, "");
                asort($poste);


                $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');
                array_pop($vrste_vpisa);
                array_unshift($vrste_vpisa, "");

                $oblik = Oblika_studija::lists('opis_oblike_studija');
                array_unshift($oblik, "");
                $nacin = Nacin_studija::lists('opis_nacina_studija');
                array_unshift($nacin, "");

                $studija = Vrsta_studija::get();
                $vrste_studija = [];
                for ($i = 0; $i < count($studija); $i++) {
                    $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija . " " . $studija[$i]->opis_vrste_studija;
                }
                array_unshift($vrste_studija, "");


                $drz = array_search(Drzava::where('sifra_drzave', $student[0]->sifra_drzave_rojstva)->pluck('naziv_drzave'),$drzave);
                $obc = array_search(Obcina::where('sifra_obcine', $student[0]->sifra_obcine_rojstva)->pluck('naziv_obcine'), $obcine);
                $drz2 = array_search(Drzava::where('sifra_drzave', $student[0]->sifra_drzave_drzavljanstva)->pluck('naziv_drzave'), $drzave);
                $nass = array_search(Posta::where('postna_stevilka', $student[0]->postna_stevilka_stalno)->pluck('naziv_poste')." ".$student[0]->postna_stevilka_stalno, $poste);
                $drzs = array_search(Drzava::where('sifra_drzave', $student[0]->sifra_drzave_stalno)->pluck('naziv_drzave'),$drzave);
                $obcs = array_search(Obcina::where('sifra_obcine', $student[0]->sifra_obcine_stalno)->pluck('naziv_obcine'),$obcine);

                $nasz = "";
                $drzz = "";
                $obcz = "";
                if(!empty($student[0]->naslov_zacasno)){
                    $nasz = array_search(Posta::where('postna_stevilka', $student[0]->postna_stevilka_zacasno)->pluck('naziv_poste')." ".$student[0]->postna_stevilka_zacasno, $poste);
                    $drzz = array_search(Drzava::where('sifra_drzave', $student[0]->sifra_drzave_zacasno)->pluck('naziv_drzave'),$drzave);
                    $obcz = array_search(Obcina::where('sifra_obcine', $student[0]->sifra_obcine_zacasno)->pluck('naziv_obcine'),$obcine);
                }

                $stdpro = array_search($zet[0]->sifra_studijskega_programa." ".Studijski_program::where('sifra_studijskega_programa',
                        $zet[0]->sifra_studijskega_programa)->pluck('naziv_studijskega_programa'), $studijski_programi);
                $vpvrs = array_search(Vrsta_vpisa::where('sifra_vrste_vpisa', $zet[0]->sifra_vrste_vpisa)->pluck('opis_vrste_vpisa'), $vrste_vpisa);
                $stdvrs = array_search($vpis->sifra_vrste_studija." ".Vrsta_studija::where('sifra_vrste_studija',
                        $vpis->sifra_vrste_studija)->pluck('opis_vrste_studija'), $vrste_studija);
                $stdnac = array_search(Nacin_studija::where('sifra_nacina_studija', $zet[0]->sifra_nacina_studija)->pluck('opis_nacina_studija'), $nacin);
                $stdobl = array_search(Oblika_studija::where('sifra_oblike_studija', $zet[0]->sifra_oblike_studija)->pluck('opis_oblike_studija'), $oblik);
                $leto = Studijsko_leto::where('sifra_studijskega_leta', $vpis->sifra_studijskega_leta)->pluck('stevilka_studijskega_leta');
                $zavod = $vpis->zavod;
                $kraj = $vpis->kraj_izvajanja;

                if($student[0]->naslov_vrocanja == $student[0]->naslov_stalno)
                    $v = true;
                else
                    $v = false;

                return view('vpisnilist', ['studijski_programi' => $studijski_programi, 'letnik' => $letnik,
                    'vrste_vpisa' => $vrste_vpisa, 'vrste_studija' => $vrste_studija, 'drzave' => $drzave, 'obcine' => $obcine,
                    'oblik' => $oblik, 'nacin' => $nacin, 'stud'=>$student[0], 'drz' => $drz, 'obc' => $obc, 'drz2'=>$drz2,
                    'drzs'=>$drzs, 'obcs'=>$obcs,'drzz'=>$drzz, 'obcz'=>$obcz, 'stdpro'=>$stdpro, 'vpvrs'=>$vpvrs,
                    'stdvrs'=> $stdvrs, 'stdnac'=>$stdnac, 'stdobl'=>$stdobl, 'leto'=>$leto, 'zavod'=>$zavod,
                    'nass'=>$nass, 'nasz'=>$nasz, 'kraj'=>$kraj, 'tip'=>1, 'poste'=>$poste, 'let'=>$let,'v'=>$v]);
            }

        }else {
            return redirect('home')->with('message', 'Neznan uporabnik!');
        }
    }
}
