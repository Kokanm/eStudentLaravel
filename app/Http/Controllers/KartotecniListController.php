<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Izpit;
use App\Letnik;
use App\Nacin_studija;
use App\Student;
use App\Studijski_program;
use App\Studijsko_leto;
use App\Vpis;
use App\Vpisan_predmet;
use App\Vrsta_vpisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KartotecniListController extends Controller {

    public function vrni(){
        $email = Auth::user()->email;

        $student = Student::where('email_studenta', $email)->get()[0];
        $vpisna = $student->vpisna_stevilka;
        $name = $student->priimek_studenta.", ".$student->ime_studenta." (".$vpisna.")";
        $active = [];
        $active[0] = "";
        $active[1] = "active";
        $programi = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->lists('sifra_studijskega_programa');
        $programi = array_unique($programi);
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]." " .Studijski_program::where('sifra_studijskega_programa', $programi[$i])->pluck('naziv_studijskega_programa');
        }
        array_unshift($studijski_programi, "");


        $leta = Izpit::where('vpisna_stevilka', $vpisna)->lists('sifra_studijskega_leta');
        $leta = array_unique($leta);
        $izpiti = [];
        $heading = [];
        for($i=0; $i<count($leta); $i++){
            $he = Vpis::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->get()[0];
            $heading[$i][0] = Studijsko_leto::where('sifra_studijskega_leta', $leta[$i])->pluck('stevilka_studijskega_leta');
            $heading[$i][1] = Letnik::where('sifra_letnika', $he->sifra_letnika)->pluck('stevilka_letnika');
            $heading[$i][2] = Vrsta_vpisa::where('sifra_vrste_vpisa', $he->sifra_vrste_vpisa)->pluck('opis_vrste_vpisa');
            $heading[$i][3] = Nacin_studija::where('sifra_nacina_studija', $he->sifra_nacina_studija)->pluck('opis_nacina_studija');
            $predmeti = Izpit::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $leta[$i])->get();
            for($j=0; $j<count($predmeti); $j++){
                $izpiti[$i][$j] = $predmeti[$j];
            }
            echo $heading[$i];
        }
        asd;
        return view('kartotecnilist',['name'=> $name, 'active'=>$active, 'studijski_programi'=>$studijski_programi, 'heading'=>$heading, 'izpiti'=>$izpiti]);
    }

}
