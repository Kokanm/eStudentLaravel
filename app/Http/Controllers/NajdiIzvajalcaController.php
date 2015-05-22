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
            'stletnik'=>$stletnik, 'stprogram'=>$stprogram, 'msg'=>[]]);
    }

    /*
    public function dodaj($izvedbe){
        $vse = Input::get();
        if(Input::get('isci')){
            return $this->najdi2($vse);
        }else {

            $prof = Profesor::get();
            $profesor = [];
            for ($i = 0; $i < count($prof); $i++) {
                $profesor[$i + 1] = $prof[$i]->ime_profesorja . " " . $prof[$i]->priimek_profesorja;
            }
            $profesor[""] = "/";
            asort($profesor);

            $izv = explode(" ", $izvedbe);
            $dup = [];
            $z = 0;
            $glupost = 2;
            foreach (array_count_values($izv) as $d) {
                if ($d == 3) {
                    $dup[$z] = 3;
                    $z += 2;
                }
                if ($d == 2) {
                    $dup[$z] = 2;
                    $z++;
                }

                $z++;
            }

            $studleto = Studijsko_leto::get();
            $leto = [];
            for ($i = 0; $i < count($studleto); $i++) {
                $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
            }
            array_unshift($leto, "");


            $stleto = $vse['stleto'];
            $stlet = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto])->pluck('sifra_studijskega_leta');


            $temp1 = null;
            $temp2 = null;
            $temp3 = null;
            $temp4 = null;
            $temp5 = null;
            $temp6 = null;
            $j = 0;

            for ($i = 0; $i < count($izv) - 1; $i++) {
                $prof1 = Input::get('prof1' . $i);
                $prof2 = Input::get('prof2' . $i);
                $prof3 = Input::get('prof3' . $i);

                $sifra1 = null;
                $sifra2 = null;
                $sifra3 = null;


                $ne = 0;

                if ($profesor[$prof1] != "/") {
                    $sifra1 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof1])[0])->where('priimek_profesorja', implode(" ", array_slice(explode(" ", $profesor[$prof1]), 1, count(explode(" ", $profesor[$prof1])) + 1)))->pluck('sifra_profesorja');
                    #echo $temp1."+".$sifra1." | ";
                    if ($temp1 == $sifra1 || $temp2 == $sifra1 || $temp3 == $sifra1) {
                        echo "PDE1";
                        $ne = 1;
                    }
                }

                if ($profesor[$prof2] != "/") {
                    $sifra2 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof2])[0])->where('priimek_profesorja', implode(" ", array_slice(explode(" ", $profesor[$prof2]), 1, count(explode(" ", $profesor[$prof2])) + 1)))->pluck('sifra_profesorja');
                    #echo $temp2."+".$sifra2." | ";
                    if ($temp1 == $sifra2 || $temp2 == $sifra2 || $temp3 == $sifra2) {
                        echo "PDE2";
                        $ne = 1;
                    }
                }else{
                    $sifra2 = null;
                }

                if ($profesor[$prof3] != "/") {

                    $sifra3 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof3])[0])->where('priimek_profesorja', implode(" ", array_slice(explode(" ", $profesor[$prof3]), 1, count(explode(" ", $profesor[$prof3])) + 1)))->pluck('sifra_profesorja');
                    #echo $temp3."+".$sifra3." | ";

                    if ($temp1 == $sifra3 || $temp2 == $sifra3 || $temp3 == $sifra3) {
                        echo "PDE3";
                        $ne = 1;
                    }
                    if($sifra2 == null){
                        $sifra2 = $sifra3;
                        $sifra3 = null;
                    }
                }else{
                    $sifra3 = null;
                }

                if($temp2 != null){
                    if($temp1 == $temp2)
                        $ne = 1;

                    if($temp3 != null)
                        if($temp2 == $temp3)
                            $ne = 1;
                        elseif($temp1 == $temp3)
                            $ne = 1;
                }


                if (array_key_exists($i, $dup) && $dup[$i] == 2) {
                    $temp1 = $sifra1;
                    $temp2 = $sifra2;
                    $temp3 = $sifra3;
                }elseif(array_key_exists($i, $dup) && $dup[$i] == 3){
                    echo "ASD";
                    asd;
                    if($glupost == 2) {
                        $temp1 = $sifra1;
                        $temp2 = $sifra2;
                        $temp3 = $sifra3;
                    }elseif($glupost == 1){
                        $temp4 = $sifra1;
                        $temp5 = $sifra2;
                        $temp6 = $sifra3;
                    }
                    $glupost--;
                }

                if ($sifra1 != $sifra2 && $sifra1 != $sifra3 && $sifra3 != $sifra2) {
                    if (count(Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta',$stlet)->get()) > 1) {
                        if ($ne == 0) {
                            $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[$j];

                            Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                            where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $sifra2]);

                            Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                            where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $sifra3]);

                            Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                            where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $sifra1]);
                            $j = $j + 1;
                            if ($j == 2) {
                                $j = 0;
                                $temp1 = null;
                                $temp2 = null;
                                $temp3 = null;
                            }
                        }
                    } else {
                        Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja' => $sifra1]);
                        Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja2' => $sifra2]);
                        Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja3' => $sifra3]);
                    }
                }else{
                    if($sifra2 == null){
                        if (count(Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta',$stlet)->get()) > 1) {
                            if ($ne == 0) {
                                $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[$j];

                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $sifra2]);

                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $sifra3]);

                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $sifra1]);
                                $j = $j + 1;
                                if ($j == 2) {
                                    $j = 0;
                                    $temp1 = null;
                                    $temp2 = null;
                                    $temp3 = null;
                                }
                            }
                        } else {
                            Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja' => $sifra1]);
                            Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja2' => $sifra2]);
                            Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja3' => $sifra3]);
                        }
                    }
                }
            }

            return $this->najdi2($vse);
        }
    }*/

    public function dodaj($izvedbe){
        $vse = Input::get();
        if(Input::get('isci')){
            return $this->najdi2($vse, []);
        }else {

            $prof = Profesor::get();
            $profesor = [];
            for ($i = 0; $i < count($prof); $i++) {
                $profesor[$i + 1] = $prof[$i]->ime_profesorja . " " . $prof[$i]->priimek_profesorja;
            }
            $profesor[""] = "/";
            asort($profesor);

            $izv = explode(" ", $izvedbe);

            $studleto = Studijsko_leto::get();
            $leto = [];
            for ($i = 0; $i < count($studleto); $i++) {
                $leto[$i] = $studleto[$i]->stevilka_studijskega_leta;
            }
            array_unshift($leto, "");


            $stleto = $vse['stleto'];
            $stlet = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto])->pluck('sifra_studijskega_leta');

            $izvajalci = [];

            for ($i = 0; $i < count($izv) - 1; $i++) {
                $prof1 = Input::get('prof1' . $i);
                $prof2 = Input::get('prof2' . $i);
                $prof3 = Input::get('prof3' . $i);

                $sifra1 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof1])[0])->where('priimek_profesorja', implode(" ", array_slice(explode(" ", $profesor[$prof1]), 1, count(explode(" ", $profesor[$prof1])) + 1)))->pluck('sifra_profesorja');
                $sifra2 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof2])[0])->where('priimek_profesorja', implode(" ", array_slice(explode(" ", $profesor[$prof2]), 1, count(explode(" ", $profesor[$prof2])) + 1)))->pluck('sifra_profesorja');
                $sifra3 = Profesor::where('ime_profesorja', explode(" ", $profesor[$prof3])[0])->where('priimek_profesorja', implode(" ", array_slice(explode(" ", $profesor[$prof3]), 1, count(explode(" ", $profesor[$prof3])) + 1)))->pluck('sifra_profesorja');


                if($sifra1 == null){
                    if($sifra2 != null) {
                        $sifra1 = $sifra2;
                        $sifra2 = $sifra3;
                        $sifra3 = null;
                    }elseif($sifra3 != null){
                        $sifra1 = $sifra3;
                        $sifra3 = null;
                    }
                }

                if($sifra2 == null){
                    if($sifra3 != null){
                        $sifra2 = $sifra3;
                        $sifra3 = null;
                    }
                }

                if($sifra1 == null){
                    $sifra1 = $i+10000;
                }

                if($sifra2 == null){
                    $sifra2 = $i+20001;
                }

                if($sifra3 == null){
                    $sifra3 = $i+30007;
                }
                
                $izvajalci[$i] = [$sifra1, $sifra2, $sifra3];
            }

            $dup = [];
            $z = 0;
            foreach (array_count_values($izv) as $d) {
                if ($d == 3) {
                    $dup[$z] = 3;
                    $dup[$z+1] = 3;
                    $dup[$z+2] = 3;
                    $z += 2;
                }
                if ($d == 2) {
                    $dup[$z] = 2;
                    $dup[$z+1] = 2;
                    $z++;
                }

                $dup[$z] = 1;

                $z++;
            }

            $messages = [];

            for ($i = 0; $i < count($izv) - 1; $i++) {
                if($dup[$i] == 1) {
                    if (max(array_count_values($izvajalci[$i])) == 1) {
                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja2' => null]);
                            if ($izvajalci[$i][2] != $i + 30007)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja3' => null]);
                            if ($izvajalci[$i][0] != $i + 10000)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->update(['sifra_profesorja' => null]);
                        }
                    }else{
                        $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: ".explode(" ", $izvedbe)[$i].")";
                    }
                }elseif($dup[$i] == 2){
                    $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[0];
                    $result = array_merge($izvajalci[$i], $izvajalci[$i+1]);
                    if (max(array_count_values($result)) == 1) {

                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => null]);
                            }

                            if ($izvajalci[$i][2] != $i + 30007) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => null]);
                            }

                            if ($izvajalci[$i][0] != $i + 10000) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => null]);
                            }
                        }
                    }
                    $i++;
                    $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[1];
                    if (max(array_count_values($result)) == 1) {
                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => null]);
                            if ($izvajalci[$i][2] != $i + 30007)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => null]);

                            if ($izvajalci[$i][0] != $i + 10000)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => null]);
                        }
                    }else{
                        $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: ".explode(" ", $izvedbe)[$i].",".explode(" ", $izvedbe)[$i-1].")";
                    }
                }else{
                    $result = array_merge($izvajalci[$i], $izvajalci[$i+1], $izvajalci[$i+2]);
                    $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[0];
                    if (max(array_count_values($result)) == 1) {
                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => null]);
                            }

                            if ($izvajalci[$i][2] != $i + 30007) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => null]);
                            }

                            if ($izvajalci[$i][0] != $i + 10000) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => null]);
                            }
                        }
                    }
                    $i++;
                    $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[1];
                    if (max(array_count_values($result)) == 1) {
                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => null]);
                            if ($izvajalci[$i][2] != $i + 30007)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => null]);
                            if ($izvajalci[$i][0] != $i + 10000)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => null]);
                        }
                    }
                    $i++;
                    $pr = Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->get()[2];
                    if (max(array_count_values($result)) == 1) {
                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja2' => null]);
                            }

                            if ($izvajalci[$i][2] != $i + 30007) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja3' => null]);
                            }

                            if ($izvajalci[$i][0] != $i + 10000) {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            } else {
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->
                                    where('sifra_profesorja', $pr->sifra_profesorja)->update(['sifra_profesorja' => null]);
                            }
                        }
                    }else{
                        $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: ".explode(" ", $izvedbe)[$i].",".explode(" ", $izvedbe)[$i-1].", ".explode(" ", $izvedbe)[$i-2].")";
                    }
                }
            }

            return $this->najdi2($vse, $messages);
        }
    }

    public function najdi2($vs, $msg)
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
            'stletnik'=>$stletnik, 'stprogram'=>$stprogram, 'msg'=>$msg]);

    }


}
