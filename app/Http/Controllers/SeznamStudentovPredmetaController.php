<?php namespace App\Http\Controllers;

use App\Izvedba_predmeta;
use App\Student;
use App\Vpis;
use App\Vpisan_predmet;
use App\Studijsko_leto;
use App\Letnik;
use App\Studijski_program;
use App\Predmet;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Vrsta_vpisa;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SeznamStudentovPredmetaController extends Controller {

	public function studentiPredmeta(){
        $stleto = Input::get( 'stleto' );
        $stletnik = Input::get( 'stletnik' );
        $stprogram = Input::get( 'stprogram' );

        for($i=0; $i<count($izvPredmeti); $i++){
            if(Input::get('brisip'.$i)){
                Izvedba_predmeta::where('id', $vs['idpredmeta'.$i])->update(['sifra_profesorja'=>null, 'sifra_profesorja2'=>null, 'sifra_profesorja3'=>null]);
            }
        }

        $subjects = Predmet::get();		
        $predmet = $stpredmet;
		$naslov[0] = Predmet::where('sifra_predmeta', $stpredmet)->naziv_predmeta;
		
        $studleto = Studijsko_leto::get();		
        $leto = $studleto[$stleto-1]->sifra_studijskega_leta;
		$naslov[1] = $studleto[$stleto-1]->stevilka_studijskega_leta;

        $let = Letnik::get();
        $letnik = $let[$stletnik-1]->sifra_letnika;
		$naslov[2] = $let[$stletnik-1]->stevilka_letnika;
		
        $studijski_programi = Studijski_program::get();
        $program = $studijski_programi[$stprogram-1]->sifra_studijskega_programa;
		$naslov[3] = $studijski_programi[$stprogram-1]->naziv_studijskega_programa;

        /*
		$studenti = Vpisan_predmet::where('vpisan_predmet.sifra_predmeta', $predmet)
						->where('vpisan_predmet.sifra_studijskega_programa', $program)
						->where('vpisan_predmet.sifra_letnika', $letnik)
						->where('vpisan_predmet.sifra_studijskega_leta_izvedbe_predmeta', $leto)
						->join('student', 'vpisan_predmet.vpisna_stevilka', '=', 'student.vpisna_stevilka')
						->join('vpis', function($join)
							{
								$join->on('vpisan_predmet.vpisna_stevilka', '=', 'vpis.vpisna_stevilka')->on('vpisan_predmet.sifra_studijskega_leta', '=', 'vpis.sifra_studijskega_leta');
							})
						->join('vrsta_vpisa', 'vpis.sifra_vrste_vpisa', '=', 'vrsta_vpisa.sifra_vrste_vpisa')
						->select('student.vpisna_stevilka AS vpisna', 'student.priimek_studenta AS priimek', 'student.ime_studenta AS ime', 'vrsta_vpisa.opis_vrste_vpisa AS vrsta_vpisa')
						->orderByRaw('priimek COLLATE utf8_slovenian_ci')
						->get();
        */

        $predmeti = Vpisan_predmet::where('sifra_predmeta', $predmet)->where('sifra_studijskega_programa', $program)->where('sifra_letnika', $letnik)->
            where('sifra_studijskega_leta', $leto)->get();

        $studenti = [];
        for($i=0; $i<count($predmeti); $i++){
            $vp = $predmeti[$i]->vpisna_stevilka;
            $studenti[$i][0] = $vp;
            $studenti[$i][1] = Student::where('vpisna_stevilka', $vp)->pluck('ime_studenta')." ".Student::where('vpisna_stevilka', $vp)->pluck('priimek_studenta');
            $studenti[$i][2] = Vrsta_vpisa::where('sifra_vrste_vpisa', Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $leto)->
                where('sifra_studijskega_programa', $program)->pluck('sifra_vrste_vpisa'))->pluck('opis_vrste_vpisa');
        }
		
		$stHidden[0] = $predmet;
		$stHidden[1] = $program;
		$stHidden[2] = $letnik;
		$stHidden[3] = $leto;
		// popravi na cel $naslov, new samo [0]
        return view('studentipredmeta', ['students' => $studenti, 'naslov' => $naslov[0], 'stHidden' => $stHidden]);
	}
	
	public function izbiraPrograma(){
        $studleto = Studijsko_leto::get();
        $leto = [];
        for ($i = 0; $i < count($studleto); $i++) {
            $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
        }
        array_unshift($leto, "");

        $let = Letnik::get();
        $letnik = [];
        for ($i = 0; $i < count($let); $i++) {
            $letnik[$i] = $let[$i]->stevilka_letnika;
            if($letnik[$i] == 0)
                $letnik[$i] = "dodatno leto";
        }
        array_unshift($letnik, "");

        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        return view('izberipredmet', ['leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi]);
	}

    public function izbiraPredmeta(){
        $studleto = Studijsko_leto::get();
        $leto = [];
        for ($i = 0; $i < count($studleto); $i++) {
            $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
        }
        array_unshift($leto, "");

        $let = Letnik::get();
        $letnik = [];
        for ($i = 0; $i < count($let); $i++) {
            $letnik[$i] = $let[$i]->stevilka_letnika;
            if($letnik[$i] == 0)
                $letnik[$i] = "dodatno leto";
        }
        array_unshift($letnik, "");

        $programi = Studijski_program::get();
        $studijski_programi = [];
        $pomos = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
            $pomos[$i] = $programi[$i]->sifra_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        $stleto = substr($leto[Input::get( 'stleto' )], 2,2);
        $stletnik = $letnik[Input::get( 'stletnik' )];
        $stprogram = $pomos[Input::get( 'stprogram' )-1];

        $subject = Izvedba_predmeta::where('sifra_studijskega_leta', 15)->where('sifra_studijskega_programa', $stprogram)->where('sifra_letnika', $stletnik)->lists('sifra_predmeta');
        $predmet = [];
        for ($i = 0; $i < count($subject); $i++) {
            $predmet[$i] = $subject[$i]. " " . Predmet::where('sifra_predmeta', $subject[$i])->pluck('naziv_predmeta');
        }

        return view('izberipredmet2', ['predmet'=> $predmet ,'leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi]);
    }

}