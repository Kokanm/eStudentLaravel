<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Studijsko_leto;
use App\Letnik;
use App\Studijski_program;
use App\Izvedba_predmeta;
use App\Predmet;
use App\Profesor;
use App\Izpitni_rok;
use App\Izpit;

use Illuminate\Support\Facades\Input;

class IzpitniRokiController extends Controller {

// REFERENT
	public function izberiStudijskiProgramInLetnik() {
		$programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        $let = Letnik::get();
        $letnik = [];
        for ($i = 0; $i < count($let); $i++) {
            $letnik[$i] = $let[$i]->stevilka_letnika;
            if($letnik[$i] == 0)
                $letnik[$i] = "dodatno leto";
        }
        array_unshift($letnik, "");

        $studleto = Studijsko_leto::get();
        $leto = [];
        for ($i = 0; $i < count($studleto); $i++) {
            $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
        }
        array_unshift($leto, "");

		return view('izpitniroki', ['program' => $studijski_programi, 'letnik' => $letnik, 'leto' => $leto]);
	}

	public function urejanjeIzpitnihRokov() {
		$programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        $let = Letnik::get();
        $letnik = [];
        for ($i = 0; $i < count($let); $i++) {
            $letnik[$i] = $let[$i]->stevilka_letnika;
            if($letnik[$i] == 0)
                $letnik[$i] = "dodatno leto";
        }
        array_unshift($letnik, "");

        $studleto = Studijsko_leto::get();
        $leto = [];
        for ($i = 0; $i < count($studleto); $i++) {
            $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
        }
        array_unshift($leto, "");


		$stleto2 = Input::get( 'stleto' );
        $stleto;
        if($stleto2 != null){
            $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('stevilka_studijskega_leta');
        }
        $stletnik2 = Input::get( 'stletnik' );
        $stletnik;
        if($stletnik2 != null){
            $stletnik = Letnik::where('stevilka_letnika', $letnik[$stletnik2])->pluck('sifra_letnika');
        }
        $stprogram2 = Input::get( 'stprogram' );
        $stprogram;
        if($stprogram2 != null){
            $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('naziv_studijskega_programa');
        }

        //echo $stleto2;
        $stletosifra = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
        $predmeti2 = Izvedba_predmeta::where('sifra_studijskega_leta', $stletosifra)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->get();

        $predmeti = [];
        for ($i = 0; $i < count($predmeti2); $i++) {
            $predmeti[$i] = $predmeti2[$i]->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja)->pluck('priimek_profesorja');
            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
                $predmeti[$i] = $predmeti[$i] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja');
            }
            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
                $predmeti[$i] = $predmeti[$i] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja');
            }
        }
        sort($predmeti);
        
        $nov = '';
        $novid = '';
        // DODAJ IZPITNI ROK
        if(Input::get('dodajIzpitniRok')) {
            $nov = Input::get( 'pred' );
            
            $predmeti3 = [];
            for ($i = 0; $i < count($predmeti2); $i++) {
                $predmeti3[$i] = $predmeti2[$i]->sifra_predmeta . " " . $predmeti2[$i]->id;
            }
            sort($predmeti3);
            $novid2 = $predmeti3[$nov];
            $novid = substr($novid2, 6);
            
            $novdatum = '';
            $novdatum2 = '';
            $novdatum2 = Input::get( 'datum' );
            $novdatum = substr($novdatum2, 6) . '-';
            $novdatum = $novdatum . substr($novdatum2, 3, -5) . '-';
            $novdatum = $novdatum . substr($novdatum2, 0, -8);
            //echo $novdatum;
            
            $novIzpitniRokPredmet = Izvedba_predmeta::where('id', $novid)->first();
            $novIzpitniRok = Izpitni_rok::create(['id_izvedbe_predmeta' => $novid, 'sifra_studijskega_leta' => $novIzpitniRokPredmet->sifra_studijskega_leta, 'sifra_letnika' => $novIzpitniRokPredmet->sifra_letnika, 'sifra_studijskega_programa' => $novIzpitniRokPredmet->sifra_studijskega_programa, 'sifra_profesorja' => $novIzpitniRokPredmet->sifra_profesorja, 'sifra_predmeta' => $novIzpitniRokPredmet->sifra_predmeta, 'datum' => $novdatum, 'ura' => Input::get( 'ura' ), 'opombe' => Input::get( 'opombe' ), 'predavalnica' => Input::get( 'predavalnica' )]);
            $novIzpitniRok->save();

            /*echo 'id_izvedbe_predmeta: ' . $novIzpitniRokPredmet->id . '<br/>';
            echo 'sifra_studijskega_leta: ' . $novIzpitniRokPredmet->sifra_studijskega_leta . '<br/>';
            echo 'sifra_letnika: ' . $novIzpitniRokPredmet->sifra_letnika . '<br/>';
            echo 'sifra_studijskega_programa: ' . $novIzpitniRokPredmet->sifra_studijskega_programa . '<br/>';
            echo 'sifra_predmeta: ' . $novIzpitniRokPredmet->sifra_predmeta . '<br/>';
            echo 'sifra_profesorja: ' . $novIzpitniRokPredmet->sifra_profesorja . '<br/>';
            echo 'ura: ' . Input::get( 'ura' ) . '<br/>';
            echo 'opombe: ' . Input::get( 'opombe' ) . '<br/>';
            echo 'predavalnica: ' . Input::get( 'predavalnica' ) . '<br/>';
            echo 'datum: ' . Input::get( 'datum' ) . '<br/>';*/

        }

        

        // PREBERI VSE IZPITNI ROKE, KI ŠE NISO MIMO
        $izpitniRoki2 = Izpitni_rok::where('sifra_studijskega_leta', $stletosifra)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->get();
        $izpitniRoki = [];

        //echo $izpitniRoki2;

        for ($i = 0; $i < count($izpitniRoki2); $i++) {
            $izpitniRoki[$i][0] = $izpitniRoki2[$i]->id;

            $temp1 = $izpitniRoki2[$i]->datum;
            $temp2 = substr($temp1, 8) . '-';
            $temp2 = $temp2 . substr($temp1, 5, -3) . '-';
            $temp2 = $temp2 . substr($temp1, 0, -6);
            $izpitniRoki[$i][1] = $temp2;

            $izpitniRoki[$i][2] = $izpitniRoki2[$i]->ura;
            $izpitniRoki[$i][3] = $izpitniRoki2[$i]->predavalnica;
            $izpitniRoki[$i][4] = $izpitniRoki2[$i]->opombe;

            $temp1 = $izpitniRoki2[$i]->id_izvedbe_predmeta;
            $temp2 = Izvedba_predmeta::where('id', $temp1)->first();
            $izpitniRoki[$i][5] = $temp2->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->pluck('priimek_profesorja');
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja');
            }
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja');
            }
            $temp1 = $izpitniRoki2[$i]->id;
            $izpitniRoki[$i][6] = Izpit::where('id_izpitnega_roka', $temp1)->count();
        }



        // ODSTRANI IZPITNI ROK
        if(Input::get('odstraniIzpitniRok')) {
            //Izpitni_rok::where('id', Input::get( 'id' ))->delete();
            //Izpit::where('id_izpitnega_roka', Input::get( 'id' ))->delete();
            
            //echo 'odstranil ' . Input::get( 'id' );

            $izpitni_rok_id = Input::get( 'id' );

            if(Izpit::where('id_izpitnega_roka', $izpitni_rok_id)->count() == 0) {
                Izpitni_rok::where('id', Input::get( 'id' ))->delete();
                Izpit::where('id_izpitnega_roka', Input::get( 'id' ))->delete();
            } else {
                return view('izpitnirokibrisi', ['program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'izpitni_rok_id' => $izpitni_rok_id]);
            }
        }
        if(Input::get('odstraniIzpitniRokPreklici')) {
            return view('izpitnirokiurejanje', ['program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'predmeti' => $predmeti, 'izpitniRoki' => $izpitniRoki]);
        }
        if(Input::get('odstraniIzpitniRokPotrdi')) {
            Izpitni_rok::where('id', Input::get( 'id' ))->delete();
            Izpit::where('id_izpitnega_roka', Input::get( 'id' ))->delete();
        }


        // ŠE ENKRAT KER SMO BRISALI IN ENEGA ROKA NI VEČ
        // PREBERI VSE IZPITNI ROKE, KI ŠE NISO MIMO
        $izpitniRoki2 = Izpitni_rok::where('sifra_studijskega_leta', $stletosifra)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->get();
        $izpitniRoki = [];

        //echo $izpitniRoki2;

        for ($i = 0; $i < count($izpitniRoki2); $i++) {
            $izpitniRoki[$i][0] = $izpitniRoki2[$i]->id;

            $temp1 = $izpitniRoki2[$i]->datum;
            $temp2 = substr($temp1, 8) . '-';
            $temp2 = $temp2 . substr($temp1, 5, -3) . '-';
            $temp2 = $temp2 . substr($temp1, 0, -6);
            $izpitniRoki[$i][1] = $temp2;

            $izpitniRoki[$i][2] = $izpitniRoki2[$i]->ura;
            $izpitniRoki[$i][3] = $izpitniRoki2[$i]->predavalnica;
            $izpitniRoki[$i][4] = $izpitniRoki2[$i]->opombe;

            $temp1 = $izpitniRoki2[$i]->id_izvedbe_predmeta;
            $temp2 = Izvedba_predmeta::where('id', $temp1)->first();
            $izpitniRoki[$i][5] = $temp2->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->pluck('priimek_profesorja');
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja');
            }
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja');
            }
            $temp1 = $izpitniRoki2[$i]->id;
            $izpitniRoki[$i][6] = Izpit::where('id_izpitnega_roka', $temp1)->count();
        }

		return view('izpitnirokiurejanje', ['program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'predmeti' => $predmeti, 'izpitniRoki' => $izpitniRoki]);
	}





///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





// PROFESOR
    public function izberiStudijskiProgramInLetnikProfesor() {
        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        $let = Letnik::get();
        $letnik = [];
        for ($i = 0; $i < count($let); $i++) {
            $letnik[$i] = $let[$i]->stevilka_letnika;
            if($letnik[$i] == 0)
                $letnik[$i] = "dodatno leto";
        }
        array_unshift($letnik, "");

        $studleto = Studijsko_leto::get();
        $leto = [];
        for ($i = 0; $i < count($studleto); $i++) {
            $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
        }
        array_unshift($leto, "");

        

        return view('izpitnirokiprofesor', ['program' => $studijski_programi, 'letnik' => $letnik, 'leto' => $leto]);
    }

    public function urejanjeIzpitnihRokovProfesor() {
        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        $let = Letnik::get();
        $letnik = [];
        for ($i = 0; $i < count($let); $i++) {
            $letnik[$i] = $let[$i]->stevilka_letnika;
            if($letnik[$i] == 0)
                $letnik[$i] = "dodatno leto";
        }
        array_unshift($letnik, "");

        $studleto = Studijsko_leto::get();
        $leto = [];
        for ($i = 0; $i < count($studleto); $i++) {
            $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
        }
        array_unshift($leto, "");


        $stleto2 = Input::get( 'stleto' );
        $stleto;
        if($stleto2 != null){
            $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('stevilka_studijskega_leta');
        }
        $stletnik2 = Input::get( 'stletnik' );
        $stletnik;
        if($stletnik2 != null){
            $stletnik = Letnik::where('stevilka_letnika', $letnik[$stletnik2])->pluck('sifra_letnika');
        }
        $stprogram2 = Input::get( 'stprogram' );
        $stprogram;
        if($stprogram2 != null){
            $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('naziv_studijskega_programa');
        }

        //echo $stleto2;

        $email = \Auth::user()->email;
        $vpisanprofesor = Profesor::where('email_profesorja', $email)->first();
        $vpisanprofesor_sifra_profesorja = $vpisanprofesor->sifra_profesorja;
        //echo $vpisanprofesor_sifra_profesorja;

        $stletosifra = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
        $predmeti2 = Izvedba_predmeta::where('sifra_studijskega_leta', $stletosifra)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->where('sifra_profesorja', $vpisanprofesor_sifra_profesorja)->get();

        $predmeti = [];
        for ($i = 0; $i < count($predmeti2); $i++) {
            $predmeti[$i] = $predmeti2[$i]->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja)->pluck('priimek_profesorja');
            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
                $predmeti[$i] = $predmeti[$i] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja');
            }
            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
                $predmeti[$i] = $predmeti[$i] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja');
            }
        }
        sort($predmeti);
        
        $nov = '';
        $novid = '';
        // DODAJ IZPITNI ROK
        if(Input::get('dodajIzpitniRok')) {
            $nov = Input::get( 'pred' );
            
            $predmeti3 = [];
            for ($i = 0; $i < count($predmeti2); $i++) {
                $predmeti3[$i] = $predmeti2[$i]->sifra_predmeta . " " . $predmeti2[$i]->id;
            }
            sort($predmeti3);
            $novid2 = $predmeti3[$nov];
            $novid = substr($novid2, 6);
            
            $novdatum = '';
            $novdatum2 = '';
            $novdatum2 = Input::get( 'datum' );
            $novdatum = substr($novdatum2, 6) . '-';
            $novdatum = $novdatum . substr($novdatum2, 3, -5) . '-';
            $novdatum = $novdatum . substr($novdatum2, 0, -8);
            //echo $novdatum;
            
            $novIzpitniRokPredmet = Izvedba_predmeta::where('id', $novid)->first();
            $novIzpitniRok = Izpitni_rok::create(['id_izvedbe_predmeta' => $novid, 'sifra_studijskega_leta' => $novIzpitniRokPredmet->sifra_studijskega_leta, 'sifra_letnika' => $novIzpitniRokPredmet->sifra_letnika, 'sifra_studijskega_programa' => $novIzpitniRokPredmet->sifra_studijskega_programa, 'sifra_profesorja' => $novIzpitniRokPredmet->sifra_profesorja, 'sifra_predmeta' => $novIzpitniRokPredmet->sifra_predmeta, 'datum' => $novdatum, 'ura' => Input::get( 'ura' ), 'opombe' => Input::get( 'opombe' ), 'predavalnica' => Input::get( 'predavalnica' )]);
            $novIzpitniRok->save();

            /*echo 'id_izvedbe_predmeta: ' . $novIzpitniRokPredmet->id . '<br/>';
            echo 'sifra_studijskega_leta: ' . $novIzpitniRokPredmet->sifra_studijskega_leta . '<br/>';
            echo 'sifra_letnika: ' . $novIzpitniRokPredmet->sifra_letnika . '<br/>';
            echo 'sifra_studijskega_programa: ' . $novIzpitniRokPredmet->sifra_studijskega_programa . '<br/>';
            echo 'sifra_predmeta: ' . $novIzpitniRokPredmet->sifra_predmeta . '<br/>';
            echo 'sifra_profesorja: ' . $novIzpitniRokPredmet->sifra_profesorja . '<br/>';
            echo 'ura: ' . Input::get( 'ura' ) . '<br/>';
            echo 'opombe: ' . Input::get( 'opombe' ) . '<br/>';
            echo 'predavalnica: ' . Input::get( 'predavalnica' ) . '<br/>';
            echo 'datum: ' . Input::get( 'datum' ) . '<br/>';*/

        }


        // PREBERI VSE IZPITNI ROKE, KI ŠE NISO MIMO
        $izpitniRoki2 = Izpitni_rok::where('sifra_studijskega_leta', $stletosifra)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->where('sifra_profesorja', $vpisanprofesor_sifra_profesorja)->get();
        $izpitniRoki = [];

        //echo $izpitniRoki2;

        for ($i = 0; $i < count($izpitniRoki2); $i++) {
            $izpitniRoki[$i][0] = $izpitniRoki2[$i]->id;

            $temp1 = $izpitniRoki2[$i]->datum;
            $temp2 = substr($temp1, 8) . '-';
            $temp2 = $temp2 . substr($temp1, 5, -3) . '-';
            $temp2 = $temp2 . substr($temp1, 0, -6);
            $izpitniRoki[$i][1] = $temp2;

            $izpitniRoki[$i][2] = $izpitniRoki2[$i]->ura;
            $izpitniRoki[$i][3] = $izpitniRoki2[$i]->predavalnica;
            $izpitniRoki[$i][4] = $izpitniRoki2[$i]->opombe;

            $temp1 = $izpitniRoki2[$i]->id_izvedbe_predmeta;
            $temp2 = Izvedba_predmeta::where('id', $temp1)->first();
            $izpitniRoki[$i][5] = $temp2->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->pluck('priimek_profesorja');
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja');
            }
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja');
            }
            $temp1 = $izpitniRoki2[$i]->id;
            $izpitniRoki[$i][6] = Izpit::where('id_izpitnega_roka', $temp1)->count();
        }


        // ODSTRANI IZPITNI ROK
        if(Input::get('odstraniIzpitniRok')) {
            //Izpitni_rok::where('id', Input::get( 'id' ))->delete();
            //Izpit::where('id_izpitnega_roka', Input::get( 'id' ))->delete();
            
            //echo 'odstranil ' . Input::get( 'id' );

            $izpitni_rok_id = Input::get( 'id' );

            if(Izpit::where('id_izpitnega_roka', $izpitni_rok_id)->count() == 0) {
                Izpitni_rok::where('id', Input::get( 'id' ))->delete();
                Izpit::where('id_izpitnega_roka', Input::get( 'id' ))->delete();
            } else {
                return view('izpitnirokibrisiprofesor', ['program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'izpitni_rok_id' => $izpitni_rok_id]);
            }
        }
        if(Input::get('odstraniIzpitniRokPreklici')) {
            return view('izpitnirokiurejanjeprofesor', ['program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'predmeti' => $predmeti, 'izpitniRoki' => $izpitniRoki]);
        }
        if(Input::get('odstraniIzpitniRokPotrdi')) {
            Izpitni_rok::where('id', Input::get( 'id' ))->delete();
            Izpit::where('id_izpitnega_roka', Input::get( 'id' ))->delete();
        }


        // ŠE ENKRAT KER SMO BRISALI IN ENEGA ROKA NI VEČ
        // PREBERI VSE IZPITNI ROKE, KI ŠE NISO MIMO
        $izpitniRoki2 = Izpitni_rok::where('sifra_studijskega_leta', $stletosifra)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->where('sifra_profesorja', $vpisanprofesor_sifra_profesorja)->get();
        $izpitniRoki = [];

        //echo $izpitniRoki2;

        for ($i = 0; $i < count($izpitniRoki2); $i++) {
            $izpitniRoki[$i][0] = $izpitniRoki2[$i]->id;

            $temp1 = $izpitniRoki2[$i]->datum;
            $temp2 = substr($temp1, 8) . '-';
            $temp2 = $temp2 . substr($temp1, 5, -3) . '-';
            $temp2 = $temp2 . substr($temp1, 0, -6);
            $izpitniRoki[$i][1] = $temp2;

            $izpitniRoki[$i][2] = $izpitniRoki2[$i]->ura;
            $izpitniRoki[$i][3] = $izpitniRoki2[$i]->predavalnica;
            $izpitniRoki[$i][4] = $izpitniRoki2[$i]->opombe;

            $temp1 = $izpitniRoki2[$i]->id_izvedbe_predmeta;
            $temp2 = Izvedba_predmeta::where('id', $temp1)->first();
            $izpitniRoki[$i][5] = $temp2->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $temp2->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->pluck('priimek_profesorja');
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->pluck('priimek_profesorja');
            }
            if (Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
                $izpitniRoki[$i][5] = $izpitniRoki[$i][5] . ", " . Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->pluck('priimek_profesorja');
            }
            $temp1 = $izpitniRoki2[$i]->id;
            $izpitniRoki[$i][6] = Izpit::where('id_izpitnega_roka', $temp1)->count();
        }

        return view('izpitnirokiurejanjeprofesor', ['program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'predmeti' => $predmeti, 'izpitniRoki' => $izpitniRoki]);
    }

}