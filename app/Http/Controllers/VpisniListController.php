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
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class VpisniListController extends Controller {

	public function vpisi(Requests\VpisniListRequest $request){
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

        $studija = Vrsta_studija::get();
        $vrste_studija = [];
        for ($i=0; $i < count($studija); $i++){
            $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija." ".$studija[$i]->opis_vrste_studija;
        }

        $letnik = Letnik::lists('stevilka_letnika');
        $list = $request->all();
        $emso = $list["emso"];
        $datum = $list["datumrojstva"];

        $std = new Student;
        $std->vpisna_stevilka = $list["vstevilka"];
        $std->ime_studenta = explode(" ", $list["imepriimek"])[0];
        $std->priimek_studenta = explode(" ", $list["imepriimek"])[1];
        $std->datum_rojstva = date("Y-m-d", strtotime($datum));
        $std->obcina_rojstva = $obcine[$list["krajrojstva"]-1];
        $std->sifra_drzave_rojstva = Drzava::where('naziv_drzave', $drzave[$list['drzavarojstva']-1])->pluck('sifra_drzave');
        $std->sifra_drzave_drzavljanstva = Drzava::where('naziv_drzave', $drzave[$list['drzavljanstvo']-1])->pluck('sifra_drzave');
        $std->spol = $list["spol"][0];

        if(substr($datum, 0, 2) == substr($emso, 0, 2) && substr($datum, 3, 2) == substr($emso, 2, 2)
            && substr($datum, 7, 3) == substr($emso, 4, 3) && substr($emso, 7, 2) > 49 && substr($emso, 7, 2) < 60)
        {
            if($list["spol"][0] == "M" && substr($emso, 9, 3) >= 0 && substr($emso, 9, 3) < 500)
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

            }elseif($list["spol"][0] == "Ž" && substr($emso, 9, 3) >= 500 && substr($emso, 9, 3) < 1000){
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
        $std->postna_stevilka_stalno = $list['posta'];
        $std->sifra_obcine_stalno = Obcina::where('naziv_obcine', $obcine[$list['obcinastalno']-1])->pluck('sifra_obcine');
        $std->sifra_drzave_stalno = Drzava::where('naziv_drzave', $drzave[$list['drzavastalno']-1])->pluck('sifra_drzave');
        $std->naslov_stalno = $list["naslovstalno"];
        if(Posta::where('postna_stevilka', $list['posta'])->pluck('naziv_poste') != $list['obcinastalno'])
            Redirect::back()->withInput()->withErrors("Napačna poštna številka!");

        $std->zeton_porabljen = 1;

        $std->save();

        $vp = new Vpis;
        $vp->sifra_studijskega_programa = explode(" ", $studijski_programi[$list['studiskiprogram']-1])[0];
        $vp->sifra_vrste_studija = explode(" ", $vrste_studija[$list['vrstastudija']-1])[0];
        $vp->sifra_vrste_vpisa = Vrsta_vpisa::where('opis_vrste_vpisa', $vrste_vpisa[$list['vrstavpisa']-1])->pluck('sifra_vrste_vpisa');

        if($list['letnikdodatno'][0] != 'd')
            $vp->sifra_letnika = $list['letnikdodatno'][0];
        else
            $vp->sifra_letnika = 7;

        $vp->sifra_nacina_studija = Nacin_studija::where('opis_nacina_studija', $nacin[$list['nacin']-1])->pluck('sifra_nacina_studija');
        $vp->sifra_oblike_studija = Oblika_studija::where('opis_oblike_studija', $oblik[$list['oblika']-1])->pluck('sifra_oblike_studija');
        $vp->sifra_studijskega_leta = Studijsko_leto::where('stevilka_studijskega_leta', date('Y')."/".(date('Y')+1))->pluck('sifra_studijskega_leta');
        $vp->vpisna_stevilka = $std->vpisna_stevilka;

        if(array_key_exists('zavod', $list)){
            $vp->zavod = $list['zavod'];
        }

        if(array_key_exists('krajizvajanja', $list)){
            $vp->kraj_izvajanja = $list['krajizvajanja'];
        }
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
            $vpisi->vpisna_stevilka = $vp->vpisna_stevilka;
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
        for ($i=0; $i < count($prosto_izbirni); $i++){
            $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                pluck('stevilo_KT')." KT";
        }
        if(!empty($prosti))
            array_unshift($prosti, "");


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

        $pomos = $vp->vpisna_stevilka.$vp->sifra_studijskega_leta.$vp->sifra_studijskega_programa.$vp->sifra_letnika;


        return view('predmeti', ['studijski_program' => $studijski_programi[$list['studiskiprogram']-1], 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
            'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $pomos]);

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

                $drzave = Drzava::lists('naziv_drzave');
                array_unshift($drzave, "");
                $obcine = Obcina::lists('naziv_obcine');
                array_unshift($obcine, "");
                $letnik = Letnik::lists('stevilka_letnika');
                array_unshift($letnik, "");
                $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');
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
                    'oblik' => $oblik, 'nacin' => $nacin, 'kand' => $kandidat[0], 'vp' => $vp]);
            } elseif ($user->type == 1){
                $student = Student::where('email_studenta', $user->email_kandidata)->get();

                $programi = Studijski_program::get();
                $studijski_programi = [];
                for ($i = 0; $i < count($programi); $i++) {
                    $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
                }
                array_unshift($studijski_programi, "");

                $drzave = Drzava::lists('naziv_drzave');
                array_unshift($drzave, "");
                $obcine = Obcina::lists('naziv_obcine');
                array_unshift($obcine, "");
                $letnik = Letnik::lists('stevilka_letnika');
                array_unshift($letnik, "");
                $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');
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
                    'oblik' => $oblik, 'nacin' => $nacin, 'stud'=>$student[0]]);
            }

        }else {
            return redirect('/');
        }
    }
}
