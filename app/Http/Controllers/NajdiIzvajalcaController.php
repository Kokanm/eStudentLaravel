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
            where('sifra_studijskega_programa',explode(" ", $studijski_programi[$stprogram])[0])->orderBy('sifra_predmeta')->get();

        $predmeti = [];
        $predmeti2 = [];
        $pomos = "";
        $z = 0;
        foreach($izvPredmeti as $izv) {
            if(!(($izv->sifra_profesorja == 0 || $izv->sifra_profesorja == null) && ($izv->sifra_profesorja2 == 0 || $izv->sifra_profesorja == null) &&
                    ($izv->sifra_profesorja3 == 0 || $izv->sifra_profesorja == null))){
                $predmeti[$izv->sifra_predmeta] = $izv->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $izv->sifra_predmeta)->pluck('naziv_predmeta');
                $pomos = $pomos . $izv->sifra_predmeta . " ";
            }else{
                unset($izvPredmeti[$z]);
            }
            $z++;
            $predmeti2[$izv->sifra_predmeta] = $izv->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $izv->sifra_predmeta)->pluck('naziv_predmeta');
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
            'predmeti'=>$predmeti, 'predmeti2'=>$predmeti2, 'izvPredmeti'=>$izvPredmeti, 'profesor'=>$profesor, 'pomos' => $pomos, 'stleto'=>$stleto,
            'stletnik'=>$stletnik, 'stprogram'=>$stprogram, 'msg'=>[]]);
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
        where('sifra_studijskega_programa',explode(" ", $studijski_programi[$stprogram])[0])->orderBy('sifra_predmeta')->get();

        $predmeti = [];
        $predmeti2 = [];
        $pomos = "";
        $z = 0;
        foreach($izvPredmeti as $izv) {
            if(!(($izv->sifra_profesorja == 0 || $izv->sifra_profesorja == null) && ($izv->sifra_profesorja2 == 0 || $izv->sifra_profesorja == null) &&
                ($izv->sifra_profesorja3 == 0 || $izv->sifra_profesorja == null))){
                $predmeti[$izv->sifra_predmeta] = $izv->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $izv->sifra_predmeta)->pluck('naziv_predmeta');
                $pomos = $pomos . $izv->sifra_predmeta . " ";
            }else{
                unset($izvPredmeti[$z]);
            }
            $z++;
            $predmeti2[$izv->sifra_predmeta] = $izv->sifra_predmeta . " " . Predmet::where('sifra_predmeta', $izv->sifra_predmeta)->pluck('naziv_predmeta');
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
            'predmeti'=>$predmeti, 'predmeti2'=>$predmeti2, 'izvPredmeti'=>$izvPredmeti, 'profesor'=>$profesor, 'pomos' => $pomos, 'stleto'=>$stleto,
            'stletnik'=>$stletnik, 'stprogram'=>$stprogram, 'msg'=>$msg]);

    }

    public function dodajPredmet($vs){
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

        if($vs['profe2'] == 0) {
            $vs['profe2'] = null;
        }

        if($vs['profe3'] == 0){
            $vs['profe3'] = null;
        }

        #if(count(Izvedba_predmeta::where()))

        $izv = new Izvedba_predmeta();
        $izv->sifra_predmeta = $vs['predmet'];
        $izv->sifra_studijskega_programa = explode(" ", $studijski_programi[$stprogram])[0];
        $izv->sifra_letnika = $stnik;
        $izv->sifra_studijskega_leta = $stlet;
        $izv->sifra_profesorja = $vs['profe1'];
        $izv->sifra_profesorja2 = $vs['profe2'];
        $izv->sifra_profesorja3 = $vs['profe3'];
        $izv->save();

        return $this->najdi2($vs, []);
    }

    public function brisiPredmet($vs){
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
        $i = 0;
        foreach($izvPredmeti as $izv) {
            $predmeti[$i] = $izv->sifra_predmeta;
            $i++;
        }

        for($i=0; $i<count($izvPredmeti); $i++){
            if(Input::get('brisip'.$i)){
                Izvedba_predmeta::where('id', $vs['idpredmeta'.$i])->update(['sifra_profesorja'=>null, 'sifra_profesorja2'=>null, 'sifra_profesorja3'=>null]);
            }
        }

        return $this->najdi2($vs, []);
    }

    public function dodaj($izvedbe){
        $vse = Input::get();
        if(Input::get('isci')){
            return $this->najdi2($vse, []);
        } elseif(Input::get('dodajp')){
            return $this->dodajPredmet($vse);
        } elseif(Input::get('posod')){
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
                $idpredmeta = Input::get('idpredmeta'.$i);
                if($dup[$i] == 1) {
                    if (max(array_count_values($izvajalci[$i])) == 1) {
                        if(min($izvajalci[$i]) != $i+10000) {
                            if ($izvajalci[$i][1] != $i + 20001)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->update(['sifra_profesorja2' => null]);
                            if ($izvajalci[$i][2] != $i + 30007)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->update(['sifra_profesorja3' => null]);
                            if ($izvajalci[$i][0] != $i + 10000)
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->update(['sifra_profesorja' => $izvajalci[$i][0]]);
                            else
                                Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->update(['sifra_profesorja' => null]);
                        }
                    }else{
                        $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: ".explode(" ", $izvedbe)[$i].")";
                    }
                }elseif($dup[$i] == 2){
                    $result = array_diff($izvajalci[$i], $izvajalci[$i+1]);
                    $filtered = array_filter($result, function ($x) { return $x <= 10000; });
                    if (!empty($filtered)) {
                        if (max(array_count_values($izvajalci[$i])) == 1) {
                            if (min($izvajalci[$i]) != $i + 10000) {
                                if ($izvajalci[$i][1] != $i + 20001) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => null]);
                                }

                                if ($izvajalci[$i][2] != $i + 30007) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => null]);
                                }

                                if ($izvajalci[$i][0] != $i + 10000) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => $izvajalci[$i][0]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => null]);
                                }
                            }
                        } else {
                            $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: " . explode(" ", $izvedbe)[$i].")";
                        }
                        $i++;
                        $idpredmeta = Input::get('idpredmeta'.$i);
                        if (max(array_count_values($izvajalci[$i])) == 1) {
                            if (min($izvajalci[$i]) != $i + 10000) {
                                if ($izvajalci[$i][1] != $i + 20001) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => null]);
                                }

                                if ($izvajalci[$i][2] != $i + 30007) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => null]);
                                }

                                if ($izvajalci[$i][0] != $i + 10000) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => $izvajalci[$i][0]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => null]);
                                }
                            }
                        } else {
                            $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: " . explode(" ", $izvedbe)[$i].")";
                        }
                    } else {
                        $messages[] = "Isto trojico profesorjev! (predmet: ". explode(" ", $izvedbe)[$i].")";
                    }
                }else {
                    $result = array_diff($izvajalci[$i], $izvajalci[$i+1]);
                    $result2 = array_diff($izvajalci[$i], $izvajalci[$i+2]);
                    $result3 = array_diff($izvajalci[$i+1], $izvajalci[$i+2]);
                    $filtered = array_filter($result, function ($x) { return $x <= 10000; });
                    $filtered2 = array_filter($result2, function ($x) { return $x <= 10000; });
                    $filtered3 = array_filter($result3, function ($x) { return $x <= 10000; });
                    if (!empty($filtered) && !empty($filtered2) && !empty($filtered3)) {
                        if (max(array_count_values($izvajalci[$i])) == 1) {
                            if (min($izvajalci[$i]) != $i + 10000) {
                                if ($izvajalci[$i][1] != $i + 20001) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => null]);
                                }

                                if ($izvajalci[$i][2] != $i + 30007) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => null]);
                                }

                                if ($izvajalci[$i][0] != $i + 10000) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => $izvajalci[$i][0]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => null]);
                                }
                            }
                        } else {
                            $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: " . explode(" ", $izvedbe)[$i].")";
                        }
                        $i++;
                        $idpredmeta = Input::get('idpredmeta'.$i);
                        if (max(array_count_values($izvajalci[$i])) == 1) {
                            if (min($izvajalci[$i]) != $i + 10000) {
                                if ($izvajalci[$i][1] != $i + 20001)
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                                else
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => null]);
                                if ($izvajalci[$i][2] != $i + 30007)
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                                else
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => null]);
                                if ($izvajalci[$i][0] != $i + 10000)
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => $izvajalci[$i][0]]);
                                else
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => null]);
                            }
                        } else {
                            $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: " . explode(" ", $izvedbe)[$i].")";
                        }
                        $i++;
                        $idpredmeta = Input::get('idpredmeta'.$i);
                        if (max(array_count_values($izvajalci[$i])) == 1) {
                            if (min($izvajalci[$i]) != $i + 10000) {
                                if ($izvajalci[$i][1] != $i + 20001) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => $izvajalci[$i][1]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja2' => null]);
                                }

                                if ($izvajalci[$i][2] != $i + 30007) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => $izvajalci[$i][2]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja3' => null]);
                                }

                                if ($izvajalci[$i][0] != $i + 10000) {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => $izvajalci[$i][0]]);
                                } else {
                                    Izvedba_predmeta::where('sifra_predmeta', explode(" ", $izvedbe)[$i])->where('sifra_studijskega_leta', $stlet)->where('id', $idpredmeta)->
                                    update(['sifra_profesorja' => null]);
                                }
                            }
                        } else {
                            $messages[] = "Ne sme biti dvakrat isti profesor! (predmet: " . explode(" ", $izvedbe)[$i].")";
                        }
                    } else {
                        $messages[] = "Isto trojico profesorjev! (predmet: ". explode(" ", $izvedbe)[$i].")";
                    }
                }
            }
            return $this->najdi2($vse, $messages);
        } else {
            return $this->brisiPredmet($vse);
        }
    }
}
