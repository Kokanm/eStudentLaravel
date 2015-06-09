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
use App\Student;
use App\Vpisan_predmet;
use DB;
use App\Vpis;

use Illuminate\Support\Facades\Input;

class IndividualniVnosKoncneOceneProfesorController extends Controller {
	public function vnesi($vp) {
		$email = \Auth::user()->email;
        $vpisanprofesor = Profesor::where('email_profesorja', $email)->first();
        $vpisanprofesor_sifra_profesorja = $vpisanprofesor->sifra_profesorja;

		$student = Student::where('vpisna_stevilka', $vp)->first();
		$student_ime = $student->ime_studenta;
		$student_priimek = $student->priimek_studenta;

		$napaka = '';
		$today = date("Y-m-d");
		//echo $today;

		// VNESI OCENO
		if(Input::get('oceni')) {
        	$id = Input::get( 'id' );
        	$ocena = Input::get( 'ocena' );
        	$datum_izpita2 = Izpit::where('id', $id)->first();
        	$datum_izpita = $datum_izpita2->datum;
        	if($ocena > -1 && $ocena < 11) {
        		if($today >= $datum_izpita) {
        			Izpit::where('id', $id)->update(['ocena'=>$ocena]);
        		} else {
        			$napaka = 'Prišlo je do napake. Študentje še niso opravljali izpita.';
        		}
        	} else {
        		$napaka = 'Prišlo je do napake. Ocena mora imeti vrednost od 1-10 ali 0 za vrnjeno prijavo.';
        	}
        }


        // PREBERI IZPITE (IZPITNE ROKE, NA KATERE JE ŠTUDENT PRIJAVLJEN), KI SO ŠE BREZ OCENE
        /*$vpis = Vpis::where('vpisna_stevilka', $vp)->orderBy('sifra_studijskega_leta', 'desc')->first();
	    $trenutno_studijsko_leto_sifra = $vpis->sifra_studijskega_leta;
	    //echo $trenutno_studijsko_leto_sifra;*/

		$izpiti2 = Izpit::where('vpisna_stevilka', $vp)->where('sifra_profesorja', $vpisanprofesor_sifra_profesorja)->get();
		$izpiti = [];
		$j=0;
        for ($i = 0; $i < count($izpiti2); $i++) {
        	if($izpiti2[$i]->ocena == null && $izpiti2[$i]->ocena !== 0 && $izpiti2[$i]->cas_odjave == null) {
	            $izpiti[$j][0] = $izpiti2[$i]->id;
	            $izpiti[$j][1] = $izpiti2[$i]->sifra_predmeta;
	            $temp1 = Predmet::where('sifra_predmeta', $izpiti[$j][1])->first();
	            $izpiti[$j][2] = $temp1->naziv_predmeta;
	            $izpiti[$j][3] = $temp1->stevilo_KT;
	            $temp1 = Izpitni_rok::where('id', $izpiti2[$i]->id_izpitnega_roka)->first();
	            $temp2 = Izvedba_predmeta::where('id', $temp1->id_izvedbe_predmeta)->first();
	            $izpiti[$j][4] = '';
	            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->first();
	            if ($temp3 != null) {
	            	$prof1 = $temp3->priimek_profesorja;
	            	$izpiti[$j][4] = $izpiti[$j][4] . $prof1;
	            }
	            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->first();
	            if ($temp3 != null) {
	            	$prof2 = $temp3->priimek_profesorja;
	            	$izpiti[$j][4] = $izpiti[$j][4] . ', ' . $prof2;
	            }
	            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->first();
	            if ($temp3 != null) {
	            	$prof3 = $temp3->priimek_profesorja;
	            	$izpiti[$j][4] = $izpiti[$j][4] . ', ' . $prof3;
	            }
	            $temp2 = $izpiti2[$i]->datum;
	            $temp3 = substr($temp2, 8) . '-';
            	$temp3 = $temp3 . substr($temp2, 5, -3) . '-';
            	$temp3 = $temp3 . substr($temp2, 0, -6);
	            $izpiti[$j][5] = $temp3;
	            $izpiti[$j][6] = $temp1->ura;
	            $temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->count();
	            $izpiti[$j][7] = $temp1+1;
	            //$temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $trenutno_studijsko_leto_sifra)->count();
	            $temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $izpiti2[$i]->sifra_studijskega_leta)->count();
	            $izpiti[$j][8] = $temp1+1;
	            $temp1 = Studijsko_leto::where('sifra_studijskega_leta', $izpiti2[$i]->sifra_studijskega_leta)->first();
	            $izpiti[$j][9] = $temp1->stevilka_studijskega_leta;

	            $j++;
        	}
        }

        // DODAJ OCENO ZA POLJUBEN IZPITNI ROK
        // PREBERI VSE PREDMETE, KI JIH JE POSLUŠAL ŠTUDENT. LAHKO SE JIM DODA OCENA ZA POLJUBEN IZPITNI ROK
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

        // IZBERI ŠTUDIJSKO LETO, PROGRAM IN LETNIK
		if(Input::get('izberi')) {
			$stleto2 = Input::get( 'stleto' );
		    $stleto;
		    if($stleto2 != null){
		        $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
		    }
	        $stletnik2 = Input::get( 'stletnik' );
	        $stletnik;
	        if($stletnik2 != null){
	            $stletnik = Letnik::where('stevilka_letnika', $letnik[$stletnik2])->pluck('sifra_letnika');
	        }
	        $stprogram2 = Input::get( 'stprogram' );
	        $stprogram;
	        if($stprogram2 != null){
	            $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('sifra_studijskega_programa');
		    }
		    
	        $predmeti2 = DB::table('vpisan_predmet')->join('izvedba_predmeta','vpisan_predmet.sifra_predmeta','=','izvedba_predmeta.sifra_predmeta')->get();
	        //echo $predmeti2[0]->sifra_studijskega_leta;
	        $predmeti = [];
	        $j = 0;
	        for ($i = 0; $i < count($predmeti2); $i++) {
	        	if ($predmeti2[$i]->sifra_studijskega_leta==$stleto && $predmeti2[$i]->sifra_letnika==$stletnik && $predmeti2[$i]->sifra_studijskega_programa==$stprogram && $predmeti2[$i]->sifra_profesorja==$vpisanprofesor_sifra_profesorja) {
		            //$predmeti[$j][0] = $predmeti2[$i]->id;	// id izvedba predmeta
		            $predmeti[$j] = $predmeti2[$i]->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja)->pluck('priimek_profesorja');
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja');
		            }
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja');
		            }
		            //$predmeti[$j] = $predmeti[$j] . $predmeti2[$i]->id;
		            $j++;
		        }
	        }
	        sort($predmeti);
	        $predmeti = array_unique($predmeti);

        	return view('individualnivnoskoncneocenepoljubenprofesor', ['vp' => $vp, 'program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'predmeti' => $predmeti]);
        }

        // IZBERI PREDMET
        if(Input::get('izberi_predmet')) {
        	$stleto2 = Input::get( 'stleto' );
		    $stleto;
		    if($stleto2 != null){
		        $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
		    }
	        $stletnik2 = Input::get( 'stletnik' );
	        $stletnik;
	        if($stletnik2 != null){
	            $stletnik = Letnik::where('stevilka_letnika', $letnik[$stletnik2])->pluck('sifra_letnika');
	        }
	        $stprogram2 = Input::get( 'stprogram' );
	        $stprogram;
	        if($stprogram2 != null){
	            $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('sifra_studijskega_programa');
		    }
		    
	        $predmeti2 = DB::table('vpisan_predmet')->join('izvedba_predmeta','vpisan_predmet.sifra_predmeta','=','izvedba_predmeta.sifra_predmeta')->get();
	        //echo $predmeti2[0]->sifra_studijskega_leta;
	        $predmeti = [];
	        $j = 0;
	        for ($i = 0; $i < count($predmeti2); $i++) {
	        	if ($predmeti2[$i]->sifra_studijskega_leta==$stleto && $predmeti2[$i]->sifra_letnika==$stletnik && $predmeti2[$i]->sifra_studijskega_programa==$stprogram && $predmeti2[$i]->sifra_profesorja==$vpisanprofesor_sifra_profesorja) {
		            //$predmeti[$j][0] = $predmeti2[$i]->id;	// id izvedba predmeta
		            $predmeti[$j] = $predmeti2[$i]->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja)->pluck('priimek_profesorja');
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja');
		            }
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja');
		            }
		            $predmeti[$j] = $predmeti[$j] . "{" . $predmeti2[$i]->id;
		            $j++;
		        }
	        }
	        sort($predmeti);
	        $predmeti = array_unique($predmeti);

        	$temp1 = Input::get( 'pred' );
        	$temp2;
	        if($temp1 != null){
	            $temp2 = $predmeti[$temp1];
		    }
		    $pos = strpos($temp2, '{');
		    $temp2 = substr($temp2, $pos+1);	// id izvedba predmeta
		    //echo $temp2;
		    $temp3 = Izpitni_rok::where('id_izvedbe_predmeta', $temp2)->get();
		    $termini = [];
        	for ($i = 0; $i < count($temp3); $i++) {
        		$temp4 = $temp3[$i]->datum;
	            $temp5 = substr($temp4, 8) . '.';
            	$temp5 = $temp5 . substr($temp4, 5, -3) . '.';
            	$temp5 = $temp5 . substr($temp4, 0, -6);
        		$termini[$i] = $temp5;
        		if($temp3[$i]->ura != null) {
        			$termini[$i] = $termini[$i] . ' ob ' . date('H:i', strtotime($temp3[$i]->ura));
        		}
        	}
        	return view('individualnivnoskoncneocenepoljubenprofesor2', ['vp' => $vp, 'program' => $stprogram, 'letnik' => $stletnik, 'leto' => $stleto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'termini' => $termini, 'pred' => $temp1]);
        }

        // IZBRALI SMO TERMIN IN VNESLI OCENO
        if(Input::get('termin_oceni')) {
        	$stleto2 = Input::get( 'stleto' );
		    $stleto;
		    if($stleto2 != null){
		        $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
		    }
	        $stletnik2 = Input::get( 'stletnik' );
	        $stletnik;
	        if($stletnik2 != null){
	            $stletnik = Letnik::where('stevilka_letnika', $letnik[$stletnik2])->pluck('sifra_letnika');
	        }
	        $stprogram2 = Input::get( 'stprogram' );
	        $stprogram;
	        if($stprogram2 != null){
	            $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('sifra_studijskega_programa');
		    }
		    
	        $predmeti2 = DB::table('vpisan_predmet')->join('izvedba_predmeta','vpisan_predmet.sifra_predmeta','=','izvedba_predmeta.sifra_predmeta')->get();
	        //echo $predmeti2[0]->sifra_studijskega_leta;
	        $predmeti = [];
	        $j = 0;
	        for ($i = 0; $i < count($predmeti2); $i++) {
	        	if ($predmeti2[$i]->sifra_studijskega_leta==$stleto && $predmeti2[$i]->sifra_letnika==$stletnik && $predmeti2[$i]->sifra_studijskega_programa==$stprogram && $predmeti2[$i]->sifra_profesorja==$vpisanprofesor_sifra_profesorja) {
		            //$predmeti[$j][0] = $predmeti2[$i]->id;	// id izvedba predmeta
		            $predmeti[$j] = $predmeti2[$i]->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja)->pluck('priimek_profesorja');
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja');
		            }
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja');
		            }
		            $predmeti[$j] = $predmeti[$j] . "{" . $predmeti2[$i]->id;
		            $j++;
		        }
	        }
	        sort($predmeti);
	        $predmeti = array_unique($predmeti);

        	$temp1 = Input::get( 'pred' );
        	$temp2;
	        if($temp1 != null){
	            $temp2 = $predmeti[$temp1];
		    }
		    $pos = strpos($temp2, '{');
		    $temp2 = substr($temp2, $pos+1);	// id izvedba predmeta
		    //echo $temp2;
		    $temp3 = Izpitni_rok::where('id_izvedbe_predmeta', $temp2)->get();
		    $termini = [];
        	for ($i = 0; $i < count($temp3); $i++) {
        		$temp4 = $temp3[$i]->datum;
	            $temp5 = substr($temp4, 8) . '.';
            	$temp5 = $temp5 . substr($temp4, 5, -3) . '.';
            	$temp5 = $temp5 . substr($temp4, 0, -6);
        		$termini[$i] = $temp5;
        		if($temp3[$i]->ura != null) {
        			$termini[$i] = $termini[$i] . ' ob ' . date('H:i', strtotime($temp3[$i]->ura));
        		}
        	}

        	$izbran_termin2 = Input::get( 'izbran_termin' );
        	$izbran_termin3 = $temp3[$izbran_termin2];
        	$izbran_termin_id_izpitni_rok = $izbran_termin3->id;
        	$izbran_termin = Izpitni_rok::where('id_izvedbe_predmeta', $temp2)->where('id', $izbran_termin_id_izpitni_rok)->first();
        	//echo $izbran_termin_id_izpitni_rok;
        	//echo $izbran_termin->sifra_profesorja;

        	// ČE IMA ŠTUDENT ZA IZBRAN TERMIN ŽE PRIJAVO, POTEM SAMO POSODOBI OCENO
        	// DRUGAČE NAREDI NOVO PRIJAVO IN VPIŠI OCENO
        	if(Input::get( 'ocena' ) > -1 && Input::get( 'ocena' ) < 11) {
	        	$nekaj = Izpit::where('vpisna_stevilka', $vp)->where('id_izpitnega_roka', $izbran_termin_id_izpitni_rok)->first();
	        	if($nekaj != null) {
	        		Izpit::where('vpisna_stevilka', $vp)->where('id_izpitnega_roka', $izbran_termin_id_izpitni_rok)->update(['ocena' => Input::get( 'ocena' )]);
	        	} else {
	        		$nekaj2 = Izpit::create(['vpisna_stevilka' => $vp, 'id_izpitnega_roka' => $izbran_termin_id_izpitni_rok, 'sifra_predmeta' => $izbran_termin->sifra_predmeta, 'sifra_studijskega_programa' => $izbran_termin->sifra_studijskega_programa, 'sifra_letnika' => $izbran_termin->sifra_letnika, 'sifra_studijskega_leta' => $izbran_termin->sifra_studijskega_leta, 'sifra_profesorja' => $izbran_termin->sifra_profesorja, 'datum' => $izbran_termin->datum, 'ocena' => Input::get( 'ocena' )]);
	        		//echo $nekaj2;
	                $nekaj2->save();
	        	}
        	} else {
        		$napaka = 'Prišlo je do napake. Ocena mora imeti vrednost od 1-10 ali 0 za vrnjeno prijavo.';
        	}

        	// KER SMO MOGOČE DODALI NOV IZPIT, ŠE ENKRAT
	        // PREBERI IZPITE (IZPITNE ROKE, NA KATERE JE ŠTUDENT PRIJAVLJEN), KI SO ŠE BREZ OCENE
	        /*$vpis = Vpis::where('vpisna_stevilka', $vp)->orderBy('sifra_studijskega_leta', 'desc')->first();
		    $trenutno_studijsko_leto_sifra = $vpis->sifra_studijskega_leta;
		    //echo $trenutno_studijsko_leto_sifra;*/

			$izpiti2 = Izpit::where('vpisna_stevilka', $vp)->where('sifra_profesorja', $vpisanprofesor_sifra_profesorja)->get();
			$izpiti = [];
			$j=0;
	        for ($i = 0; $i < count($izpiti2); $i++) {
	        	if($izpiti2[$i]->ocena == null && $izpiti2[$i]->ocena !== 0 && $izpiti2[$i]->cas_odjave == null) {
		            $izpiti[$j][0] = $izpiti2[$i]->id;
		            $izpiti[$j][1] = $izpiti2[$i]->sifra_predmeta;
		            $temp1 = Predmet::where('sifra_predmeta', $izpiti[$j][1])->first();
		            $izpiti[$j][2] = $temp1->naziv_predmeta;
		            $izpiti[$j][3] = $temp1->stevilo_KT;
		            $temp1 = Izpitni_rok::where('id', $izpiti2[$i]->id_izpitnega_roka)->first();
		            $temp2 = Izvedba_predmeta::where('id', $temp1->id_izvedbe_predmeta)->first();
		            $izpiti[$j][4] = '';
		            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->first();
		            if ($temp3 != null) {
		            	$prof1 = $temp3->priimek_profesorja;
		            	$izpiti[$j][4] = $izpiti[$j][4] . $prof1;
		            }
		            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->first();
		            if ($temp3 != null) {
		            	$prof2 = $temp3->priimek_profesorja;
		            	$izpiti[$j][4] = $izpiti[$j][4] . ', ' . $prof2;
		            }
		            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->first();
		            if ($temp3 != null) {
		            	$prof3 = $temp3->priimek_profesorja;
		            	$izpiti[$j][4] = $izpiti[$j][4] . ', ' . $prof3;
		            }
		            $temp2 = $izpiti2[$i]->datum;
		            $temp3 = substr($temp2, 8) . '-';
	            	$temp3 = $temp3 . substr($temp2, 5, -3) . '-';
	            	$temp3 = $temp3 . substr($temp2, 0, -6);
		            $izpiti[$j][5] = $temp3;
		            $izpiti[$j][6] = $temp1->ura;
		            $temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->count();
		            $izpiti[$j][7] = $temp1+1;
		            //$temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $trenutno_studijsko_leto_sifra)->count();
		            $temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $izpiti2[$i]->sifra_studijskega_leta)->count();
		            $izpiti[$j][8] = $temp1+1;
		            $temp1 = Studijsko_leto::where('sifra_studijskega_leta', $izpiti2[$i]->sifra_studijskega_leta)->first();
		            $izpiti[$j][9] = $temp1->stevilka_studijskega_leta;

		            $j++;
	        	}
	        }

        	return view('individualnivnoskoncneoceneprofesor', ['vp' => $vp, 'student_ime' => $student_ime, 'student_priimek' => $student_priimek, 'izpiti' => $izpiti, 'program' => $studijski_programi, 'letnik' => $letnik, 'leto' => $leto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'napaka' => $napaka]);
        }
        

        // IZBRALI SMO POLJUBEN DATUM IN VNESLI OCENO
        if(Input::get('termin_nov_oceni')) {
        	$stleto2 = Input::get( 'stleto' );
		    $stleto;
		    if($stleto2 != null){
		        $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
		    }
	        $stletnik2 = Input::get( 'stletnik' );
	        $stletnik;
	        if($stletnik2 != null){
	            $stletnik = Letnik::where('stevilka_letnika', $letnik[$stletnik2])->pluck('sifra_letnika');
	        }
	        $stprogram2 = Input::get( 'stprogram' );
	        $stprogram;
	        if($stprogram2 != null){
	            $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('sifra_studijskega_programa');
		    }
		    
	        $predmeti2 = DB::table('vpisan_predmet')->join('izvedba_predmeta','vpisan_predmet.sifra_predmeta','=','izvedba_predmeta.sifra_predmeta')->get();
	        //echo $predmeti2[0]->sifra_studijskega_leta;
	        $predmeti = [];
	        $j = 0;
	        for ($i = 0; $i < count($predmeti2); $i++) {
	        	if ($predmeti2[$i]->sifra_studijskega_leta==$stleto && $predmeti2[$i]->sifra_letnika==$stletnik && $predmeti2[$i]->sifra_studijskega_programa==$stprogram && $predmeti2[$i]->sifra_profesorja==$vpisanprofesor_sifra_profesorja) {
		            //$predmeti[$j][0] = $predmeti2[$i]->id;	// id izvedba predmeta
		            $predmeti[$j] = $predmeti2[$i]->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('naziv_predmeta') . " (" . Predmet::where('sifra_predmeta', $predmeti2[$i]->sifra_predmeta)->pluck('stevilo_KT') . "KT) - " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja)->pluck('priimek_profesorja');
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja2)->pluck('priimek_profesorja');
		            }
		            if (Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja') != null) {
		                $predmeti[$j] = $predmeti[$j] . ", " . Profesor::where('sifra_profesorja', $predmeti2[$i]->sifra_profesorja3)->pluck('priimek_profesorja');
		            }
		            $predmeti[$j] = $predmeti[$j] . "{" . $predmeti2[$i]->sifra_profesorja;
		            $j++;
		        }
	        }
	        sort($predmeti);
	        $predmeti = array_unique($predmeti);

        	$temp1 = Input::get( 'pred' );
        	$temp2;
	        if($temp1 != null){
	            $temp2 = $predmeti[$temp1];
		    }
		    $pos = strpos($temp2, '{');
		    $sifra_profesorja_nekaj = substr($temp2, $pos+1);	// sifra profesorja
		    #echo $sifra_profesorja_nekaj;
		    $sifra_predmeta_nekaj = substr($temp2, 0, 5);
		    #echo $sifra_predmeta_nekaj;
		    

		    $novdatum = '';
            $novdatum2 = '';
            $novdatum2 = Input::get( 'datepicker2' );
            $novdatum = substr($novdatum2, 6) . '-';
            $novdatum = $novdatum . substr($novdatum2, 3, -5) . '-';
            $novdatum = $novdatum . substr($novdatum2, 0, -8);

            if(Input::get( 'ocena' ) > 0 && Input::get( 'ocena' ) < 11) {
	        	$nekaj2 = Izpit::create(['vpisna_stevilka' => $vp, 'sifra_predmeta' => $sifra_predmeta_nekaj, 'sifra_studijskega_programa' => $stprogram, 'sifra_letnika' => $stletnik, 'sifra_studijskega_leta' => $stleto, 'sifra_profesorja' => $sifra_profesorja_nekaj, 'datum' => $novdatum, 'ocena' => Input::get( 'ocena' )]);
	        	//echo $nekaj2;
	            $nekaj2->save();
	        } else {
	        	$napaka = 'Prišlo je do napake. Ocena mora imeti vrednost od 1-10.';
	        }
        	

			// KER SMO MOGOČE DODALI NOV IZPIT, ŠE ENKRAT
	        // PREBERI IZPITE (IZPITNE ROKE, NA KATERE JE ŠTUDENT PRIJAVLJEN), KI SO ŠE BREZ OCENE
	        /*$vpis = Vpis::where('vpisna_stevilka', $vp)->orderBy('sifra_studijskega_leta', 'desc')->first();
		    $trenutno_studijsko_leto_sifra = $vpis->sifra_studijskega_leta;
		    //echo $trenutno_studijsko_leto_sifra;*/

			$izpiti2 = Izpit::where('vpisna_stevilka', $vp)->where('sifra_profesorja', $vpisanprofesor_sifra_profesorja)->get();
			$izpiti = [];
			$j=0;
	        for ($i = 0; $i < count($izpiti2); $i++) {
	        	if($izpiti2[$i]->ocena == null && $izpiti2[$i]->ocena !== 0 && $izpiti2[$i]->cas_odjave == null) {
		            $izpiti[$j][0] = $izpiti2[$i]->id;
		            $izpiti[$j][1] = $izpiti2[$i]->sifra_predmeta;
		            $temp1 = Predmet::where('sifra_predmeta', $izpiti[$j][1])->first();
		            $izpiti[$j][2] = $temp1->naziv_predmeta;
		            $izpiti[$j][3] = $temp1->stevilo_KT;
		            $temp1 = Izpitni_rok::where('id', $izpiti2[$i]->id_izpitnega_roka)->first();
		            $temp2 = Izvedba_predmeta::where('id', $temp1->id_izvedbe_predmeta)->first();
		            $izpiti[$j][4] = '';
		            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja)->first();
		            if ($temp3 != null) {
		            	$prof1 = $temp3->priimek_profesorja;
		            	$izpiti[$j][4] = $izpiti[$j][4] . $prof1;
		            }
		            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja2)->first();
		            if ($temp3 != null) {
		            	$prof2 = $temp3->priimek_profesorja;
		            	$izpiti[$j][4] = $izpiti[$j][4] . ', ' . $prof2;
		            }
		            $temp3 = Profesor::where('sifra_profesorja', $temp2->sifra_profesorja3)->first();
		            if ($temp3 != null) {
		            	$prof3 = $temp3->priimek_profesorja;
		            	$izpiti[$j][4] = $izpiti[$j][4] . ', ' . $prof3;
		            }
		            $temp2 = $izpiti2[$i]->datum;
		            $temp3 = substr($temp2, 8) . '-';
	            	$temp3 = $temp3 . substr($temp2, 5, -3) . '-';
	            	$temp3 = $temp3 . substr($temp2, 0, -6);
		            $izpiti[$j][5] = $temp3;
		            $izpiti[$j][6] = $temp1->ura;
		            $temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->count();
		            $izpiti[$j][7] = $temp1+1;
		            //$temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $trenutno_studijsko_leto_sifra)->count();
		            $temp1 = Izpit::where('sifra_predmeta', $izpiti[$j][1])->where('vpisna_stevilka', $vp)->where('ocena', '>', 0)->where('sifra_studijskega_leta', $izpiti2[$i]->sifra_studijskega_leta)->count();
		            $izpiti[$j][8] = $temp1+1;
		            $temp1 = Studijsko_leto::where('sifra_studijskega_leta', $izpiti2[$i]->sifra_studijskega_leta)->first();
		            $izpiti[$j][9] = $temp1->stevilka_studijskega_leta;

		            $j++;
	        	}
	        }


        	return view('individualnivnoskoncneoceneprofesor', ['vp' => $vp, 'student_ime' => $student_ime, 'student_priimek' => $student_priimek, 'izpiti' => $izpiti, 'program' => $studijski_programi, 'letnik' => $letnik, 'leto' => $leto, 'stprogram2' => $stprogram2, 'stletnik2' => $stletnik2, 'stleto2' => $stleto2, 'napaka' => $napaka]);
        }

        return view('individualnivnoskoncneoceneprofesor', ['vp' => $vp, 'student_ime' => $student_ime, 'student_priimek' => $student_priimek, 'izpiti' => $izpiti, 'program' => $studijski_programi, 'letnik' => $letnik, 'leto' => $leto, 'napaka' => $napaka]);
	}
}