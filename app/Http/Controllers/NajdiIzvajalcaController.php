<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Izvedba_predmeta;
use App\Letnik;
use App\Predmet;
use App\Profesor;
use App\Studijsko_leto;
use App\Studijski_program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class NajdiIzvajalcaController extends Controller {

    public function lista()
    {
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

        return view('dodajizvajalca', ['leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi]);
    }

    public function najdi()
    {
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

        $stleto = Input::get( 'stleto' );
        $stletnik = Input::get( 'stletnik' );
        $stprogram = Input::get( 'stprogram' );

        $stlet = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto])->pluck('sifra_studijskega_leta');
        $stnik = Letnik::where('stevilka_letnika', $letnik[$stletnik])->pluck('sifra_letnika');

        $izvPredmeti = Izvedba_predmeta::where('sifra_studijskega_leta', $stlet)->where('sifra_letnika', $stnik)->
        where('sifra_studijskega_programa',explode(" ", $studijski_programi[$stprogram])[0])->get();
        $predmeti = [];
        $pomos = "";
        foreach($izvPredmeti as $izv) {
            $predmeti[$izv->sifra_predmeta] = $izv->sifra_predmeta." ".Predmet::where('sifra_predmeta', $izv->sifra_predmeta)->pluck('naziv_predmeta');
            $pomos = $pomos.$izv->sifra_predmeta." ";
        }

        $prof = Profesor::get();
        $profesor = [];
        for($i=0; $i<count($prof); $i++)
        {
            $profesor[$i+1] = $prof[$i]->ime_profesorja." ".$prof[$i]->priimek_profesorja;
        }
        $profesor[""]="/";
        asort($profesor);

        return view('dodajizvajalca', ['leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi,
            'predmeti'=>$predmeti, 'izvPredmeti'=>$izvPredmeti, 'profesor'=>$profesor, 'pomos' => $pomos, 'stleto'=>$stleto,
            'stletnik'=>$stletnik, 'stprogram'=>$stprogram]);
    }

    public function dodaj($izvedbe){

        $prof = Profesor::get();
        $profesor = [];
        for($i=0; $i<count($prof); $i++)
        {
            $profesor[$i+1] = $prof[$i]->ime_profesorja." ".$prof[$i]->priimek_profesorja;
        }
        $profesor[""]="/";
        asort($profesor);

        $izv = explode(" ",$izvedbe);
        $dup = [];
        $z = 0;
        foreach(array_count_values($izv) as $d){
            if($d == 3) {
                $dup[$z] = 3;
                $z += 2;
            }
            if($d == 2) {
                $dup[$z] = 2;
                $z++;
            }

            $z++;
        }

        $temp1 = null;
        $temp2 = null;
        $temp3 = null;
        $j = 0;

        for($i=0; $i<count($izv)-1; $i++) {
            $prof1 = Input::get('prof1' . $i);
            $prof2 = Input::get('prof2' . $i);
            $prof3 = Input::get('prof3' . $i);

            $sifra1 = null;
            $sifra2 = null;
            $sifra3 = null;


            $ne = 0;

            if ($profesor[$prof1] != "/") {
                $sifra1 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof1])[0])->where('priimek_profesorja', implode(" ",array_slice(explode(" ", $profesor[$prof1]), 1, count(explode(" ", $profesor[$prof1]))+1)))->pluck('sifra_profesorja');
                if($temp1 != null && $temp1 == $sifra1){
                    $ne = 1;
                }
            }

            if ($profesor[$prof2] != "/") {
                $sifra2 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof2])[0])->where('priimek_profesorja', implode(" ",array_slice(explode(" ", $profesor[$prof2]), 1, count(explode(" ", $profesor[$prof2]))+1)))->pluck('sifra_profesorja');
                if($temp2 != null && $temp2 == $sifra2){
                    $ne = 1;
                }
            }

            if ($profesor[$prof3] != "/") {
                $sifra3 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof3])[0])->where('priimek_profesorja', implode(" ",array_slice(explode(" ", $profesor[$prof3]), 1, count(explode(" ", $profesor[$prof3]))+1)))->pluck('sifra_profesorja');
                if($temp3 != null && $temp3 == $sifra3){
                    $ne = 1;
                }
            }


            if(array_key_exists($i, $dup) && $dup[$i] == 2){
                $temp1 = $sifra1;
                $temp2 = $sifra2;
                $temp3 = $sifra3;
            }

            if($sifra1!=$sifra2 && $sifra1!=$sifra3 && $sifra3!=$sifra2) {
                if(Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get() != null) {
                    if($ne == 0) {
                        $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[$j];
                        Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $sifra1]);
                        Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $sifra2]);
                        Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $sifra3]);
                        $j++;
                        if($j==1){
                            $j = 0;
                            $temp1 = null;
                            $temp2 = null;
                            $temp3 = null;
                        }
                    }else{
                        $ne = 0;
                    }
                }else{
                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->update(['sifra_profesorja' => $sifra1]);
                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->update(['sifra_profesorja2' => $sifra2]);
                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->update(['sifra_profesorja3' => $sifra3]);
                }
            }
        }

        $vse = Input::get();

        return $this->najdi2($vse);
    }

    public function najdi2($vs)
    {
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

        $stleto = $vs['stleto'];
        $stletnik = $vs['stletnik'];
        $stprogram = $vs['stprogram'];

        $stlet = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto])->pluck('sifra_studijskega_leta');
        $stnik = Letnik::where('stevilka_letnika', $letnik[$stletnik])->pluck('sifra_letnika');

        $izvPredmeti = Izvedba_predmeta::where('sifra_studijskega_leta', $stlet)->where('sifra_letnika', $stnik)->
        where('sifra_studijskega_programa',explode(" ", $studijski_programi[$stprogram])[0])->get();
        $predmeti = [];
        $pomos = "";
        foreach($izvPredmeti as $izv) {
            $predmeti[$izv->sifra_predmeta] = $izv->sifra_predmeta." ".Predmet::where('sifra_predmeta', $izv->sifra_predmeta)->pluck('naziv_predmeta');
            $pomos = $pomos.$izv->sifra_predmeta." ";
        }

        $prof = Profesor::get();
        $profesor = [];
        for($i=0; $i<count($prof); $i++)
        {
            $profesor[$i+1] = $prof[$i]->ime_profesorja." ".$prof[$i]->priimek_profesorja;
        }
        $profesor[""]="/";
        asort($profesor);

        return view('dodajizvajalca', ['leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi,
            'predmeti'=>$predmeti, 'izvPredmeti'=>$izvPredmeti, 'profesor'=>$profesor, 'pomos' => $pomos, 'stleto'=>$stleto,
            'stletnik'=>$stletnik, 'stprogram'=>$stprogram]);
    }


}
