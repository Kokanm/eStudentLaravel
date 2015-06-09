<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Izvedba_predmeta;
use App\Predmet;
use App\Predmet_studijskega_programa;
use App\Profesor;
use App\Sestavni_del_predmetnika;
use App\Student;
use App\Studijski_program;
use App\Vpis;
use App\Vpisan_predmet;
use App\Zeton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SpremeniIzbirniPredmetiController extends Controller {

	public function izbirni($vpisna){
        $stdleto = substr(date('Y'), 2, 2);
        $vpis = Vpis::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $stdleto)->first();
        $stdpro = $vpis->sifra_studijskega_programa;
        $letnik = $vpis->sifra_letnika;
        $ime = Student::where('vpisna_stevilka', $vpisna)->pluck('priimek_studenta').", ".Student::where('vpisna_stevilka', $vpisna)->pluck('ime_studenta');

        if($vpis){
            $strokovno_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $stdpro)->
            where('sifra_letnika', $letnik)->where('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
            $strokovni = [];
            for ($i=0; $i < count($strokovno_izbirni); $i++){
                $strokovni[$i] = Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->
                    pluck('stevilo_KT')." KT";
            }
            if(!empty($strokovni))
                array_unshift($strokovni, "");

            $modpredmeti = Predmet_studijskega_programa::where('sifra_studijskega_programa', $stdpro)->where('sifra_letnika', $letnik)->
                where('sifra_sestavnega_dela','!=', '6')->where('sifra_sestavnega_dela', '!=', '7')->whereNotNull('sifra_sestavnega_dela')->lists('sifra_predmeta');
            $modularni = [];
            for($i=0; $i<count($modpredmeti); $i++) {
                $modularni[$i] = Predmet::where('sifra_predmeta', $modpredmeti[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $modpredmeti[$i])->
                    pluck('stevilo_KT')." KT";
            }
            if(!empty($modularni))
                array_unshift($modularni, "");

            if($letnik == 2) {
                $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $stdpro)->
                    where('sifra_letnika', $letnik)->whereBetween('sifra_sestavnega_dela', [6,7])->lists('sifra_predmeta');
                $prosti = [];
                for ($i = 0; $i < count($prosto_izbirni); $i++) {
                    $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                        pluck('stevilo_KT') . " KT";
                }
                if (!empty($prosti))
                    array_unshift($prosti, "");
            } else {
                $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $stdpro)->
                    where('sifra_letnika', $letnik)->whereNotNull('sifra_sestavnega_dela')->lists('sifra_predmeta');
                $prosti = [];
                $j = 0;
                for ($i = 0; $i < count($prosto_izbirni); $i++) {
                    $prosti[$j] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                        pluck('stevilo_KT') . " KT";
                    $j++;
                }
                if (!empty($prosti))
                    array_unshift($prosti, "");
            }

            $vpisan = Vpisan_predmet::where('vpisna_stevilka', $vpisna)->where('sifra_studijskega_leta', $stdleto)->lists('sifra_predmeta');
            $str = 0;
            $pr1 = 0;
            $pr2 = 0;
            $mod1 = 0;
            $mod2 = 0;
            $mod3 = 0;
            $mod4 = 0;
            $mod5 = 0;
            $mod6 = 0;
            for($i=0; $i<count($vpisan); $i++){
                $psp = Predmet_studijskega_programa::where('sifra_studijskega_programa', $stdpro)->
                    where('sifra_predmeta', $vpisan[$i])->pluck('sifra_sestavnega_dela');

                if($letnik == 2) {
                    if ($psp == 6 && $str == 0) {
                        $str = array_search($vpisan[$i], $strokovno_izbirni)+1;
                    }else {
                        if (($psp == 7 || $psp == 6) && $pr1 == 0) {
                            $pr1 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        }

                        if (($psp == 7 || $psp == 6) && $pr2 == 0) {
                            $pr2 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        }
                    }
                }elseif($letnik == 3){
                    if($psp != null && ($psp < 6 || $psp > 7)){
                        if($mod1 == 0){
                            $mod1 = array_search($vpisan[$i], $modpredmeti)+1;
                        }elseif($mod2 == 0){
                            $mod2 = array_search($vpisan[$i], $modpredmeti)+1;
                        }elseif($mod3 == 0){
                            $mod3 = array_search($vpisan[$i], $modpredmeti)+1;
                        }elseif($mod4 == 0){
                            $mod4 = array_search($vpisan[$i], $modpredmeti)+1;
                        }elseif($mod5 == 0){
                            $mod5 = array_search($vpisan[$i], $modpredmeti)+1;
                        }elseif($mod6 == 0){
                            $mod6 = array_search($vpisan[$i], $modpredmeti)+1;
                        }elseif($pr1 == 0) {
                            $pr1 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        }elseif ($pr2 == 0) {
                            $pr2 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        }
                    }

                    if(($psp == 6 || $psp == 7) && $pr1 == 0) {
                        $pr1 = array_search($vpisan[$i], $prosto_izbirni)+1;
                    }elseif (($psp == 6 || $psp == 7) && $pr2 == 0) {
                        $pr2 = array_search($vpisan[$i], $prosto_izbirni)+1;
                    }
                }
            }
            return view('spremeniizbirni', ['modularni'=>$modularni, 'strokovni'=>$strokovni, 'prosti'=>$prosti, 'mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,
                'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str, 'vp'=>$vpisna, 'ime'=>$ime]);
        }else{
            return view('home');
        }
    }

    public function izberi($vp){
        $stdleto = substr(date('Y'), 2, 2);
        $list = Input::all();
        $vpis = Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $stdleto)->first();
        $ime = Student::where('vpisna_stevilka', $vp)->pluck('priimek_studenta').", ".Student::where('vpisna_stevilka', $vp)->pluck('ime_studenta');
        $vpisna['vpisna'] = $vp;
        $vpisna['sifra_studijskega_leta'] = $stdleto;
        $vpisna['sifra_studijskega_programa'] = $vpis->sifra_studijskega_programa;
        $vpisna['sifra_letnika'] = $vpis->sifra_letnika;

        $strokovno_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
        $strokovni = [];
        for ($i=0; $i < count($strokovno_izbirni); $i++){
            $strokovni[$i] = Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->
                pluck('stevilo_KT')." KT";
        }
        if(!empty($strokovni))
            array_unshift($strokovni, "");

        $moduli = [];
        if($vpisna['sifra_letnika'] == 3) {
            $moduli = Sestavni_del_predmetnika::where('sifra_sestavnega_dela', '!=', '6')->where('sifra_sestavnega_dela', '!=', '7')->lists('opis_sestavnega_dela');
            array_unshift($moduli, "");
        }

        $modpredmeti = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->where('sifra_letnika', $vpisna['sifra_letnika'])->
        where('sifra_sestavnega_dela','!=', '6')->where('sifra_sestavnega_dela', '!=', '7')->whereNotNull('sifra_sestavnega_dela')->lists('sifra_predmeta');
        $modularni = [];
        for($i=0; $i<count($modpredmeti); $i++) {
            $modularni[$i] = Predmet::where('sifra_predmeta', $modpredmeti[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $modpredmeti[$i])->
                pluck('stevilo_KT')." KT";
        }
        if(!empty($modularni))
            array_unshift($modularni, "");

        if($vpisna['sifra_letnika'] == 2) {
            $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_letnika', $vpisna['sifra_letnika'])->whereBetween('sifra_sestavnega_dela', [6,7])->lists('sifra_predmeta');
            $prosti = [];
            for ($i = 0; $i < count($prosto_izbirni); $i++) {
                $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                    pluck('stevilo_KT') . " KT";
            }
            if (!empty($prosti))
                array_unshift($prosti, "");
        } else {
            $prosto_izbirni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_letnika', $vpisna['sifra_letnika'])->whereNotNull('sifra_sestavnega_dela')->lists('sifra_predmeta');
            $prosti = [];
            $j = 0;
            for ($i = 0; $i < count($prosto_izbirni); $i++) {
                $prosti[$j] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta') . " - " . Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                    pluck('stevilo_KT') . " KT";
                $j++;
            }
            if (!empty($prosti))
                array_unshift($prosti, "");
        }

        $vpisan = Vpisan_predmet::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $stdleto)->lists('sifra_predmeta');
        $str = 0;
        $pr1 = 0;
        $pr2 = 0;
        $mod1 = 0;
        $mod2 = 0;
        $mod3 = 0;
        $mod4 = 0;
        $mod5 = 0;
        $mod6 = 0;
        $strz = 0;
        $prz1 = 0;
        $prz2 = 0;
        $modz = array_fill(1, 6, 0);
        for($i=0; $i<count($vpisan); $i++){
            $psp = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_predmeta', $vpisan[$i])->pluck('sifra_sestavnega_dela');

            if($vpisna['sifra_letnika'] == 2) {
                if ($psp == 6 && $str == 0) {
                    $str = array_search($vpisan[$i], $strokovno_izbirni)+1;
                    $strz = $vpisan[$i];
                }else {
                    if (($psp == 7 || $psp == 6) && $pr1 == 0) {
                        $pr1 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        $prz1 = $vpisan[$i];
                    }

                    if (($psp == 7 || $psp == 6) && $pr2 == 0) {
                        $pr2 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        $prz2 = $vpisan[$i];
                    }
                }
            }elseif($vpisna['sifra_letnika'] == 3){
                if($psp != null && ($psp < 6 || $psp > 7)){
                    if($mod1 == 0){
                        $mod1 = array_search($vpisan[$i], $modpredmeti)+1;
                        $modz[1] = $vpisan[$i];
                    }elseif($mod2 == 0){
                        $mod2 = array_search($vpisan[$i], $modpredmeti)+1;
                        $modz[2] = $vpisan[$i];
                    }elseif($mod3 == 0){
                        $mod3 = array_search($vpisan[$i], $modpredmeti)+1;
                        $modz[3] = $vpisan[$i];
                    }elseif($mod4 == 0){
                        $mod4 = array_search($vpisan[$i], $modpredmeti)+1;
                        $modz[4] = $vpisan[$i];
                    }elseif($mod5 == 0){
                        $mod5 = array_search($vpisan[$i], $modpredmeti)+1;
                        $modz[5] = $vpisan[$i];
                    }elseif($mod6 == 0){
                        $mod6 = array_search($vpisan[$i], $modpredmeti)+1;
                        $modz[6] = $vpisan[$i];
                    }elseif($pr1 == 0) {
                        $pr1 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        $prz1 = $vpisan[$i];
                    }elseif ($pr2 == 0) {
                        $pr2 = array_search($vpisan[$i], $prosto_izbirni)+1;
                        $prz2 = $vpisan[$i];
                    }
                }

                if(($psp == 6 || $psp == 7) && $pr1 == 0) {
                    $pr1 = array_search($vpisan[$i], $prosto_izbirni)+1;
                    $prz1 = $vpisan[$i];
                }elseif (($psp == 6 || $psp == 7) && $pr2 == 0) {
                    $pr2 = array_search($vpisan[$i], $prosto_izbirni)+1;
                    $prz2 = $vpisan[$i];
                }
            }
        }

        $stp = Studijski_program::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->pluck('naziv_studijskega_programa');

        if(array_key_exists('prosti', $list) && array_key_exists('prosti2', $list)) {
            if ($list['prosti'] == $list['prosti2'] && $list['prosti'] != 0) {
                if ($vpisna['sifra_letnika'] == 3) {
                    return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3, 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2,
                            'str' => $str, 'ime' => $ime,'studijski_program' => $stp,  'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli,
                            'vp' => $vp, 'modularni' => $modularni, 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
                } else {
                    return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3, 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
                }
            }
        }

        if(array_key_exists('strokovni', $list)) {
            if ($list['prosti'] == $list['strokovni'] && $list['prosti'] != 0) {
                return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,                 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp, 
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
            }

            if ($list['prosti2'] == $list['strokovni'] && $list['prosti2'] != 0) {
                return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,                 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp, 
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
            }
        }else{
            for($i=1; $i<=6; $i++){
                if ($modularni[$list['modularni' . $i]-1] == $prosti[$list['prosti']-1] && $list['modularni' . $i] != 0) {
                    return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,                 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp, 
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni'=>$modularni, 'tips'=>0])->
                    withErrors("Izberite različne modularne predmete! ");
                }
            }
        }

        if(array_key_exists('modularni' . 1, $list)){
            for($i=1; $i<=5; $i++){
                for($j=$i+1; $j<=6; $j++) {
                    if($list['modularni' . $i] != 0) {
                        if ($list['modularni' . $i] == $list['modularni' . $j]) {
                            return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,                 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp, 
                                'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni' => $modularni, 'tips' => 0])->
                            withErrors("Izberite različne modularne predmete! ");
                        }
                    }else{
                        return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,                 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp, 
                            'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni' => $modularni, 'tips' => 0])->
                        withErrors("Izberite modularne predmete! ");
                    }
                }
            }
            for($i=1; $i<=6; $i++) {
                if ($list['modularni' . $i] != 0) {
                    Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_leta',  $vpisna['sifra_studijskega_leta'])->
                        where('sifra_predmeta', $modz[$i])->update(['sifra_predmeta'=>$modpredmeti[$list['modularni'.$i]-1]]);
                }
            }
        }

        if(array_key_exists('prosti', $list)){
            if($list['prosti'] != 0) {
                Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_leta',  $vpisna['sifra_studijskega_leta'])->
                    where('sifra_predmeta', $prz1)->update(['sifra_predmeta'=>$prosto_izbirni[$list['prosti']-1]]);
            }
        }

        if(array_key_exists('prosti2', $list)){
            if($list['prosti2'] != 0) {
                Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_leta',  $vpisna['sifra_studijskega_leta'])->
                    where('sifra_predmeta', $prz2)->update(['sifra_predmeta'=>$prosto_izbirni[$list['prosti2']-1]]);
            }
        }

        if(array_key_exists('strokovni', $list)){
            if($list['strokovni'] != 0) {
                Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_leta',  $vpisna['sifra_studijskega_leta'])->
                    where('sifra_predmeta', $strz)->update(['sifra_predmeta'=>$strokovno_izbirni[$list['strokovni']-1]]);
            }else{
                return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,  'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,
                    'ime' => $ime,'studijski_program' => $stp, 'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni'=>[], 'tips'=>1])->withErrors("Izberite en strokovni predmet!");
            }
        }

        $predmeti = Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_studijskega_leta', $vpisna['sifra_studijskega_leta'])->lists('sifra_predmeta');
        $suma = 0;
        for($i=0; $i<count($predmeti); $i++){
            $suma += Predmet::where('sifra_predmeta', $predmeti[$i])->pluck('stevilo_KT');
        }

        if($suma < 60) {
            if($vpisna['sifra_letnika']==3 && $vpisna['zeton'] == 1) {
                return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3,  'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni'=>$modularni, 'tips'=>1])->withErrors("Nimate dovolj KT!");
            }else{
                return view('spremeniizbirni', ['mod1'=>$mod1, 'mod2'=>$mod2, 'mod3'=>$mod3, 'mod4'=>$mod4, 'mod5'=>$mod5, 'mod6'=>$mod6, 'pr1'=>$pr1, 'pr2'=>$pr2, 'str' => $str,'ime' => $ime,'studijski_program' => $stp,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vp' => $vp, 'modularni'=>[], 'tips'=>1])->withErrors("Nimate dovolj KT!");
            }
        }

        return redirect('home')->with('message', 'Predmeti so bili spremenjeni!');
    }
}
