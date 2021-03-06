<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Kandidat;
use App\Student;
use App\User;
use App\Vpis;
use App\Letnik;
use App\Nacin_studija;
use App\Studijski_program;
use App\Vpisan_predmet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PotrditevVpisaController extends Controller {

    public function nepotrjeni()
    {
        $vpisi = Vpis::where('sifra_studijskega_leta', substr(date('Y'), 2, 2))->where('vpis_potrjen', 0)->lists('vpisna_stevilka');

        if (count($vpisi) > 0) {

            $studenti = [];
            for ($i = 0; $i < count($vpisi); $i++) {
                $studenti[$i] = Student::where('vpisna_stevilka', $vpisi[$i])->get();
            }

            return view('nepotrjeni', ['studenti' => $studenti, 'pom'=>0]);
        }else{
            echo "VSE JE POTRJENO";
        }
    }

	public function potrdi($vs){
        $student = Student::where('vpisna_stevilka', $vs)->get();
        $vpis = Vpis::where('vpisna_stevilka', $vs)->where('sifra_studijskega_leta', substr(date('Y'), 2, 2))->get();

        $vse['vpisnastevilka'] = $vs;
        $vse['priimekime'] = $student[0]->priimek_studenta . ', ' . $student[0]->ime_studenta;
        $vse['datum'] = date("d.m.Y", strtotime($student[0]->datum_rojstva));
        $vse['kraj'] = $student[0]->kraj_rojstva;
        $vse['letnik'] = Letnik::where('sifra_letnika', $vpis[0]->sifra_letnika)->pluck('stevilka_letnika');
        $vse['nacin'] = Nacin_studija::where('sifra_nacina_studija', $vpis[0]->sifra_nacina_studija)->pluck('opis_nacina_studija');
        $vse['program'] = Studijski_program::where('sifra_studijskega_programa', $vpis[0]->sifra_studijskega_programa)->pluck('naziv_studijskega_programa');
        Vpis::where('vpisna_stevilka', $vs)->where('sifra_studijskega_leta', substr(date('Y'), 2, 2))->update(['vpis_potrjen'=>1]);
        User::where('email', $student[0]->email_studenta)->update(['type'=>1]);
        Kandidat::where('email_kandidata', $student[0]->email_studenta)->delete();
        $st = 5;
        return view('potrdiloovpisu', ['vse'=>$vse, 'st'=>$st]);
    }

    public function natisni($vs){
        $student = Student::where('vpisna_stevilka', $vs)->get();
        $vpis = Vpis::where('vpisna_stevilka', $vs)->where('sifra_studijskega_leta', substr(date('Y'), 2, 2))->get();

        $vse['vpisnastevilka'] = $vs;
        $vse['priimekime'] = $student[0]->priimek_studenta . ', ' . $student[0]->ime_studenta;
        $vse['datum'] = date("d.m.Y", strtotime($student[0]->datum_rojstva));
        $vse['kraj'] = $student[0]->kraj_rojstva;
        $vse['letnik'] = Letnik::where('sifra_letnika', $vpis[0]->sifra_letnika)->pluck('stevilka_letnika');
        $vse['nacin'] = Nacin_studija::where('sifra_nacina_studija', $vpis[0]->sifra_nacina_studija)->pluck('opis_nacina_studija');
        $vse['program'] = Studijski_program::where('sifra_studijskega_programa', $vpis[0]->sifra_studijskega_programa)->pluck('naziv_studijskega_programa');
        $st = Input::get('stevilo')-1;

        return view('potrdiloovpisu', ['vse'=>$vse, 'st'=>$st]);
    }

}
