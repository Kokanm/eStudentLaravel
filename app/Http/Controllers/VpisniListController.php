<?php namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Request;

class VpisniListController extends Controller {

	public function vpisi(){
        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i=0; $i < count($programi); $i++){
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa." ".$programi[$i]->naziv_studijskega_programa;
        }

        $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');

        $studija = Vrsta_studija::get();
        $vrste_studija = [];
        for ($i=0; $i < count($studija); $i++){
            $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija." ".$studija[$i]->opis_vrste_studija;
        }

        $letnik = Letnik::lists('stevilka_letnika');
        $list = Input::all();

        $std = new Student;
        $std->vpisna_stevilka = $list["vstevilka"];
        $std->ime_studenta = explode(" ",$list["priimekime"])[1];
        $std->priimek_studenta = explode(" ",$list["priimekime"])[0];
        $std->datum_rojstva = date("Y-m-d", strtotime($list["datumrojstva"]));
        $std->kraj_rojstva = $list["krajrojstva"];
        $std->sifra_drzave_rojstva = Drzava::where('naziv_drzave', str_replace(' ', '', explode(",", $list['drzavarojstva'])[0]))->pluck('sifra_drzave');
        $std->sifra_drzave_drzavljanstva = Drzava::where('naziv_drzave', str_replace(' ', '', explode(",", $list['drzavljanstvo'])[0]))->pluck('sifra_drzave');
        $std->spol = $list["spol"][0];
        $std->emso = $list["emso"];
        $std->davcna_stevilka = $list["davcna"];
        $std->email_studenta = $list["email"];
        $std->prenosni_telefon = $list["gsm"];
        $std->postna_stevilka_stalno = Posta::where('naziv_poste', str_replace(' ', '', explode(",", $list['obcinastalno'])[1]))->pluck('postna_stevilka');
        $std->sifra_obcine_stalno = Obcina::where('naziv_obcine', str_replace(' ', '', explode(",", $list['obcinastalno'])[1]))->pluck('sifra_obcine');
        $std->sifra_drzave_stalno = Drzava::where('naziv_drzave', str_replace(' ', '', explode(",", $list['obcinastalno'])[0]))->pluck('sifra_drzave');
        $std->naslov_stalno = $list["naslovstalno"];
        $std->save();

        $vp = new Vpis;
        $vp->sifra_studijskega_programa = explode(" ", $studijski_programi[$list['studiskiprogram']])[0];
        $vp->sifra_vrste_studija = explode(" ", $vrste_studija[$list['vrstastudija']])[0];
        $vp->sifra_vrste_vpisa = Vrsta_vpisa::where('opis_vrste_vpisa', $vrste_vpisa[$list['vrstavpisa']])->pluck('sifra_vrste_vpisa');
        $vp->sifra_letnika = Letnik::where('stevilka_letnika', $letnik[$list['letnikdodatno']])->pluck('sifra_letnika');
        $vp->sifra_nacina_studija = Nacin_studija::where('opis_nacina_studija', str_replace(' ', '', explode(",", $list['nacinoblika'])[0]))->pluck('sifra_nacina_studija');
        $vp->sifra_oblike_studija = Oblika_studija::where('opis_oblike_studija', str_replace(' ', '', explode(",", $list['nacinoblika'])[1]))->pluck('sifra_oblike_studija');
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
            $prosti[count($prosto_izbirni)] = "";
        }
        if(!empty($prosti))
            $prosti[count($prosto_izbirni)] = "";


        $strokovno_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->
        where('sifra_letnika', $vp->sifra_letnika)->where('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
        $strokovni = [];
        for ($i=0; $i < count($strokovno_izbirni); $i++){
            $strokovni[$i] = Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->
                pluck('stevilo_KT')." KT";
        }
        if(!empty($strokovni))
            $strokovni[count($strokovno_izbirni)] = "";


        $moduli = [];
        if($vp->sifra_letnika == 3) {
            $moduli = Sestavni_del_predmetnika::where('sifra_sestavnega_dela', '!=', '6')->where('sifra_sestavnega_dela', '!=', '7')->lists('opis_sestavnega_dela');
            $moduli[count($moduli)] = "";
        }

        $pomos = $vp->vpisna_stevilka.$vp->sifra_studijskega_leta.$vp->sifra_studijskega_programa.$vp->sifra_letnika;

        return view('predmeti', ['studijski_program' => $studijski_programi[$list['studiskiprogram']], 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
        'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $pomos]);
    }

    public function select(){
        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i=0; $i < count($programi); $i++){
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa." ".$programi[$i]->naziv_studijskega_programa;
        }

        $letnik = Letnik::lists('stevilka_letnika');
        $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');

        $studija = Vrsta_studija::get();
        $vrste_studija = [];
        for ($i=0; $i < count($studija); $i++){
            $vrste_studija[$i] = $studija[$i]->sifra_vrste_studija." ".$studija[$i]->opis_vrste_studija;
        }

        return view('vpisnilist', ['studijski_programi' => $studijski_programi, 'letnik' => $letnik, 'vrste_vpisa' => $vrste_vpisa, 'vrste_studija' => $vrste_studija]);
    }
}
