<?php namespace App\Http\Controllers;

use App\Letnik;
use App\Nacin_studija;
use App\Oblika_studija;
use App\Predmet_studijskega_programa;
use App\Predmet;
use App\Student;
use App\Drzava;
use App\Posta;
use App\Obcina;
use App\Studijsko_leto;
use App\Vpis;
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

        $letnik = Letnik::lists('stevilka_letnika');
        $list = Input::all();

        /*
        $std = new Student;
        $std->vpisna_stevilka = $list["vstevilka"];
        $std->ime_studenta = explode(" ",$list["priimekime"])[1];
        $std->priimek_studenta = explode(" ",$list["priimekime"])[0];
        $std->datum_rojstva = date("Y-m-d", strtotime($list["datumrojstva"]));
        $std->kraj_rojstva = $list["krajrojstva"];
        $std->sifra_drzave_rojstva = Drzava::where('naziv_drzave', str_replace(' ', '', explode(",", $list['drzavarojstva'])[0]))->pluck('sifra_drzave');
        $std->sifra_drzave_drzavljanstvo = Drzava::where('naziv_drzave', str_replace(' ', '', explode(",", $list['drzavljanstvo'])[0]))->pluck('sifra_drzave');
        $std->spol = $list["spol"][0];
        $std->emso = $list["emso"];
        $std->davcna_stevilka = $list["davcna"];
        $std->email_studenta = $list["email"];
        $std->prenosni_telefon = $list["gsm"];
        $std->postna_stevilka_stalno = Posta::where('naziv_poste', str_replace(' ', '', explode(",", $list['obcinastalno'])[1]))->pluck('postna_stevilka');
        $std->sifra_obcine_stalno = Obcina::where('naziv_obcine', str_replace(' ', '', explode(",", $list['obcinastalno'])[1]))->pluck('sifra_obcine');
        $std->sifra_drzave_stalno = Drzava::where('naziv_drzave', str_replace(' ', '', explode(",", $list['obcinastalno'])[0]))->pluck('sifra_drzave');
        $std->naslov_stalno = $list["naslovstalno"];
        */

        $vp = new Vpis;
        $vp->sifra_studijskega_programa = explode(" ", $studijski_programi[$list['studiskiprogram']])[0];
        $vp->sifra_vrste_studija = explode(" ", $list['vrstastudija'])[0];
        $vp->sifra_vrste_vpisa = Vrsta_vpisa::where('opis_vrste_vpisa', $vrste_vpisa[$list['vrstavpisa']])->pluck('sifra_vrste_vpisa');
        $vp->sifra_letnika = Letnik::where('stevilka_letnika', $letnik[$list['letnikdodatno']])->pluck('sifra_letnika');
        $vp->sifra_nacina_studija = Nacin_studija::where('opis_nacina_studija', str_replace(' ', '', explode(",", $list['nacinoblika'])[0]))->pluck('sifra_nacina_studija');
        $vp->sifra_oblike_studija = Oblika_studija::where('opis_oblike_studija', str_replace(' ', '', explode(",", $list['nacinoblika'])[1]))->pluck('sifra_oblike_studija');
        $vp->sifra_studijskega_leta = Studijsko_leto::where('stevilka_studijskega_leta', $list['letoprvegavpisa'])->pluck('sifra_studijskega_leta');
        $vp->vpisna_stevilka = 63100303;
        $vp->save();
        #$std->save();

        $obvezni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vp->sifra_studijskega_programa)->where('sifra_letnika', $vp->sifra_letnika)->lists('sifra_predmeta');
        $obvezni_predmeti = [];
        for($i=0; $i<count($obvezni); $i++){
            $obvezni_predmeti[$i] = Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('naziv_predmeta');
        }

        return view('predmeti', ['studijski_program' => $studijski_programi[$list['studiskiprogram']], 'predmeti'=>$obvezni_predmeti]);
    }

    public function select(){
        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i=0; $i < count($programi); $i++){
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa." ".$programi[$i]->naziv_studijskega_programa;
        }

        $letnik = Letnik::lists('stevilka_letnika');
        $vrste_vpisa = Vrsta_vpisa::lists('opis_vrste_vpisa');

        return view('vpisnilist', ['studijski_programi' => $studijski_programi, 'letnik' => $letnik, 'vrste_vpisa' => $vrste_vpisa]);
    }
}
