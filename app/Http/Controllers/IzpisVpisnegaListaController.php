<?php namespace App\Http\Controllers;

use App\Drzava;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Letnik;
use App\Nacin_studija;
use App\Obcina;
use App\Oblika_studija;
use App\Student;
use App\Studijski_program;
use App\Vpis;
use App\Profesor;
use App\Izvedba_predmeta;
Use App\Predmet;
use App\Vpisan_predmet;
use App\Vrsta_studija;
use App\Vrsta_vpisa;
use Illuminate\Http\Request;

class IzpisVpisnegaListaController extends Controller {

	public function vpisnilist($vs){
        $student = Student::where('vpisna_stevilka', $vs)->get();
        $vpis = Vpis::where('vpisna_stevilka', $vs)->get();
        $program =  Vpisan_predmet::where('vpisna_stevilka', $vs)->
            pluck('sifra_studijskega_programa')." ".Studijski_program::where('sifra_studijskega_programa', Vpisan_predmet::where('vpisna_stevilka', $vs)->
            pluck('sifra_studijskega_programa'))->pluck('naziv_studijskega_programa');
        $predmeti = Vpisan_predmet::where('vpisna_stevilka', $vs)->lists('sifra_predmeta');
        $predmet = [];
        $sum = 0;
        for($i=0; $i<count($predmeti); $i++){
            $predmet[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $predmeti[$i])->
            pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$predmeti[$i])->
            pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $predmeti[$i])->
            pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $predmeti[$i])->pluck('stevilo_KT')];
            $sum += Predmet::where('sifra_predmeta', $predmeti[$i])->pluck('stevilo_KT');
        }

        $vse = [];
        $vse['vpisnastevilka'] = $vs;
        $vse['priimekime'] = $student[0]->priimek_studenta . ', ' . $student[0]->ime_studenta;
        $vse['datum'] = $student[0]->datum_rojstva;
        $vse['kraj'] = $student[0]->kraj_rojstva;
        $vse['drzava'] = Drzava::where('sifra_drzave', $student[0]->sifra_drzave_rojstva)->pluck('naziv_drzave') . ', ' . $vse['kraj'];
        $vse['drzavljanstvo'] = Drzava::where('sifra_drzave', $student[0]->sifra_drzave_drzavljanstvo)->pluck('naziv_drzave');
        if($student[0]->spol == 'M')
            $vse['spol'] = "Moški";
        else
            $vse['spol'] = "Ženski";

        if($student[0]->emso)
            $vse['emso'] = $student[0]->emso;
        else
            $vse['emso'] = "";

        if($student[0]->davcna_stevilka)
            $vse['ds'] = $student[0]->davcna_stevilka;
        else
            $vse['ds'] = "";

        $vse['email'] = $student[0]->email_studenta;

        if($student[0]->prenosni_telefon)
            $vse['gsm'] = $student[0]->prenosni_telefon;
        else
            $vse['gsm'] = "";

        $vse['naslov1'] = $student[0]->naslov_stalno;
        $vse['obcina1'] = Obcina::where('sifra_obcine', $student[0]->sifra_obcine_stalno)->pluck('naziv_obcine');

        if($student[0]->naslov_zacasno)
            $vse['naslov2'] = $student[0]->naslov_zacasno;
        else
            $vse['naslov2'] = "";

        if($student[0]->obcina_zacasno)
            $vse['obcina2'] = Obcina::where('sifra_obcine', $student[0]->sifra_obcine_zacasno)->pluck('naziv_obcine');
        else
            $vse['obcina2'] = "";

        $vse['program'] = $vpis[0]->sifra_studijskega_programa." ".Studijski_program::where('sifra_studijskega_programa', $vpis[0]->sifra_studijskega_programa)->pluck('naziv_studijskega_programa');

        $vse['krajizvajanja'] = "Ljubljana";
        $vse['vrstastudija'] = Vrsta_studija::where('sifra_vrste_studija', $vpis[0]->sifra_vrste_studija)->pluck('opis_vrste_studija');
        $vse['vrstevpisa'] = Vrsta_vpisa::where('sifra_vrste_vpisa', $vpis[0]->sifra_vrste_vpisa)->pluck('opis_vrste_vpisa');

        $vse['letnik'] = Letnik::where('sifra_letnika', $vpis[0]->sifra_letnika)->pluck('stevilka_letnika');
        $vse['nacinoblika'] = Nacin_studija::where('sifra_nacina_studija', $vpis[0]->sifra_nacina_studija)->pluck('opis_nacina_studija') .', '. Oblika_studija::where('sifra_oblike_studija', $vpis[0]->sifra_oblike_studija)->pluck('opis_oblike_studija');
        $vse['prvivpis'] = '20'.$vs[2].$vs[3].'/20'.$vs[2].($vs[3]+1);
        $vse['potrdi'] = 1;
        return view('izpisvpisnegalista', ['vse'=>$vse, 'predmeti'=>$predmet, 'sum'=>$sum, 'studijski_program'=> $program]);
    }

    public function pregled($vs){
        $student = Student::where('vpisna_stevilka', $vs)->get();
        $vpis = Vpis::where('vpisna_stevilka', $vs)->get();
        $program =  Vpisan_predmet::where('vpisna_stevilka', $vs)->
        pluck('sifra_studijskega_programa')." ".Studijski_program::where('sifra_studijskega_programa', Vpisan_predmet::where('vpisna_stevilka', $vs)->
        pluck('sifra_studijskega_programa'))->pluck('naziv_studijskega_programa');
        $predmeti = Vpisan_predmet::where('vpisna_stevilka', $vs)->lists('sifra_predmeta');
        $predmet = [];
        $sum = 0;
        for($i=0; $i<count($predmeti); $i++){
            $predmet[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $predmeti[$i])->
            pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$predmeti[$i])->
            pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $predmeti[$i])->
            pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $predmeti[$i])->pluck('stevilo_KT')];
            $sum += Predmet::where('sifra_predmeta', $predmeti[$i])->pluck('stevilo_KT');
        }

        $vse = [];
        $vse['vpisnastevilka'] = $vs;
        $vse['priimekime'] = $student[0]->priimek_studenta . ', ' . $student[0]->ime_studenta;
        $vse['datum'] = $student[0]->datum_rojstva;
        $vse['kraj'] = $student[0]->kraj_rojstva;
        $vse['drzava'] = Drzava::where('sifra_drzave', $student[0]->sifra_drzave_rojstva)->pluck('naziv_drzave') . ', ' . $vse['kraj'];
        $vse['drzavljanstvo'] = Drzava::where('sifra_drzave', $student[0]->sifra_drzave_drzavljanstva)->pluck('naziv_drzave');
        if($student[0]->spol == 'M')
            $vse['spol'] = "Moški";
        else
            $vse['spol'] = "Ženski";

        if($student[0]->emso)
            $vse['emso'] = $student[0]->emso;
        else
            $vse['emso'] = "";

        if($student[0]->davcna_stevilka)
            $vse['ds'] = $student[0]->davcna_stevilka;
        else
            $vse['ds'] = "";

        $vse['email'] = $student[0]->email_studenta;

        if($student[0]->prenosni_telefon)
            $vse['gsm'] = $student[0]->prenosni_telefon;
        else
            $vse['gsm'] = "";

        $vse['naslov1'] = $student[0]->naslov_stalno;
        $vse['obcina1'] = Obcina::where('sifra_obcine', $student[0]->sifra_obcine_stalno)->pluck('naziv_obcine');

        if($student[0]->naslov_zacasno)
            $vse['naslov2'] = $student[0]->naslov_zacasno;
        else
            $vse['naslov2'] = "";

        if($student[0]->obcina_zacasno)
            $vse['obcina2'] = Obcina::where('sifra_obcine', $student[0]->sifra_obcine_zacasno)->pluck('naziv_obcine');
        else
            $vse['obcina2'] = "";

        $vse['program'] = $vpis[0]->sifra_studijskega_programa." ".Studijski_program::where('sifra_studijskega_programa', $vpis[0]->sifra_studijskega_programa)->pluck('naziv_studijskega_programa');

        $vse['krajizvajanja'] = "Ljubljana";
        $vse['vrstastudija'] = Vrsta_studija::where('sifra_vrste_studija', $vpis[0]->sifra_vrste_studija)->pluck('opis_vrste_studija');
        $vse['vrstevpisa'] = Vrsta_vpisa::where('sifra_vrste_vpisa', $vpis[0]->sifra_vrste_vpisa)->pluck('opis_vrste_vpisa');

        $vse['letnik'] = Letnik::where('sifra_letnika', $vpis[0]->sifra_letnika)->pluck('stevilka_letnika');
        $vse['nacinoblika'] = Nacin_studija::where('sifra_nacina_studija', $vpis[0]->sifra_nacina_studija)->pluck('opis_nacina_studija') .', '. Oblika_studija::where('sifra_oblike_studija', $vpis[0]->sifra_oblike_studija)->pluck('opis_oblike_studija');
        $vse['prvivpis'] = '20'.$vs[2].$vs[3].'/20'.$vs[2].($vs[3]+1);

        return view('izpisvpisnegalista', ['vse'=>$vse, 'predmeti'=>$predmet, 'sum'=>$sum, 'studijski_program'=> $program]);
    }

}
