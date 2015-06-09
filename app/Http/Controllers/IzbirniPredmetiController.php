<?php namespace App\Http\Controllers;

use App\Predmet_studijskega_programa;
use App\Predmet;
use App\Profesor;
use App\Izvedba_predmeta;
use App\Student;
use App\Studijski_program;
use App\Sestavni_del_predmetnika;
use App\Vpis;
use App\Vpisan_predmet;
use App\Zeton;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IzbirniPredmetiController extends Controller {

	public function izberi($vp){

        $list = Input::all();
        $vpisna['vpisna'] = substr($vp, 0,8);
        $vpisna['sifra_studijskega_leta'] = substr($vp, 8,2);
        $vpisna['sifra_studijskega_programa'] = substr($vp, 10,7);
        $vpisna['sifra_letnika'] = substr($vp, 17,1);
        $vpisna['zeton'] = substr($vp, 18,1);

        Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_studijskega_leta', $vpisna['sifra_studijskega_leta'])->delete();

        $obvezni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', NULL)->lists('sifra_predmeta');
        $obvezni_predmeti = [];
        $sum = 0;
        if(!Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_studijskega_leta', $vpisna['sifra_studijskega_leta'])->count()) {
            for($i=0; $i<count($obvezni); $i++){
                $obvezni_predmeti[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $obvezni[$i])->
                pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT')];
                $vpisi = new Vpisan_predmet();
                $vpisi->vpisna_stevilka = $vpisna['vpisna'];
                $vpisi->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $vpisi->sifra_predmeta = $obvezni[$i];
                $vpisi->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $vpisi->sifra_letnika = $vpisna['sifra_letnika'];
                $vpisi->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $vpisi->save();

                $sum += Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT');
            }
        }else{
            for($i=0; $i<count($obvezni); $i++){
                $obvezni_predmeti[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $obvezni[$i])->
                pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT')];
                $sum += Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT');
            }
        }

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

        $stp = Studijski_program::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->pluck('naziv_studijskega_programa');

        if(array_key_exists('modul', $list) && array_key_exists('modul2', $list))
            if($list['modul2'] == $list['modul'])
                return view('predmeti', ['studijski_program' => $stp, 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips'=>0])->withErrors("Izberite različne module!");
            elseif($list['modul2'] == 0 || $list['modul'] == 0)
                return view('predmeti', ['studijski_program' => $stp, 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips'=>0])->withErrors("Izberite modul!");

        if(array_key_exists('prosti', $list) && array_key_exists('prosti2', $list)) {
            if ($list['prosti'] == $list['prosti2'] && $list['prosti'] != 0) {
                if ($vpisna['sifra_letnika'] == 3 && $vpisna['zeton'] == 1) {
                    return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => $modularni, 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
                } else {
                    return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
                }
            }
        }

        if(array_key_exists('strokovni', $list)) {
            if ($list['prosti'] == $list['strokovni'] && $list['prosti'] != 0) {
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
            }

            if ($list['prosti2'] == $list['strokovni'] && $list['prosti2'] != 0) {
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
            }
        }elseif($vpisna['sifra_letnika'] == 3 && $vpisna['zeton'] == 1){
            for($i=1; $i<=6; $i++){
                if ($modularni[$list['modularni' . $i]] == $prosti[$list['prosti']] && $list['modularni' . $i] != 0) {
                    return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>$modularni, 'tips'=>0])->
                        withErrors("Izberite različne modularne predmete! ");
                }
            }
        }else{
            $module1 = $list['modul'];
            $module2 = $list['modul2'];
            if($list['modul'] == 6 || $list['modul']==7){
                $module1 = $list['modul']+2;
            }
            if($list['modul2'] == 6 || $list['modul2']==7){
                $module2 = $list['modul2']+2;
            }

            $mod1 = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
                where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', $module1)->lists('sifra_predmeta');
            $mod2 = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
                where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', $module2)->lists('sifra_predmeta');

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti']])[0])->pluck('sifra_predmeta'), $mod1)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                    withErrors("Izberite različne modularne predmete! ");
            }

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti']])[0])->pluck('sifra_predmeta'), $mod2)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                    withErrors("Izberite različne modularne predmete! ");
            }

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti2']])[0])->pluck('sifra_predmeta'), $mod1)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                    withErrors("Izberite različne modularne predmete! ");
            }

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti2']])[0])->pluck('sifra_predmeta'), $mod2)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                    withErrors("Izberite različne modularne predmete! ");
            }
        }

        if(array_key_exists('modularni' . 1, $list)){
            for($i=1; $i<=5; $i++){
                for($j=$i+1; $j<=6; $j++) {
                    if($list['modularni' . $i] != 0) {
                        if ($list['modularni' . $i] == $list['modularni' . $j]) {
                            return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                                'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => $modularni, 'tips' => 0])->
                            withErrors("Izberite različne modularne predmete! ");
                        }
                    }else{
                        return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                            'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => $modularni, 'tips' => 0])->
                            withErrors("Izberite modularne predmete! ");
                    }
                }
            }
            for($i=1; $i<=6; $i++) {
                if ($list['modularni' . $i] != 0) {
                    $pre = new Vpisan_predmet();
                    $pre->vpisna_stevilka = $vpisna['vpisna'];
                    $pre->sifra_predmeta = $modpredmeti[$list['modularni'.$i]-1];
                    $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                    $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                    $pre->sifra_letnika = $vpisna['sifra_letnika'];
                    $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                    $pre->save();
                }
            }
        }

        if(array_key_exists('prosti', $list)){
            if($list['prosti'] != 0) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $prosto_izbirni[$list['prosti'] - 1];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }
        }

        if(array_key_exists('prosti2', $list)){
            if($list['prosti2'] != 0) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $prosto_izbirni[$list['prosti2'] - 1];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }
        }

        if(array_key_exists('strokovni', $list)){
            if($list['strokovni'] != 0) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $strokovno_izbirni[$list['strokovni'] - 1];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }else{
                return view('predmeti', ['studijski_program' => $stp, 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>1])->withErrors("Izberite en strokovni predmet!");
            }
        }

        if(array_key_exists('modul', $list)){
            if($list['modul']==6 || $list['modul']==7){
                $list['modul'] = $list['modul']+2;
            }

            $modul = Predmet_studijskega_programa::where('sifra_sestavnega_dela', $list['modul'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
                where('sifra_letnika', $vpisna['sifra_letnika'])->lists('sifra_predmeta');
            for($i=0; $i<count($modul); $i++) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $modul[$i];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }
        }

        if(array_key_exists('modul2', $list)){
            if($list['modul2']==6 || $list['modul2']==7){
                $list['modul2'] = $list['modul2']+2;
            }

            $modul = Predmet_studijskega_programa::where('sifra_sestavnega_dela', $list['modul2'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->where('sifra_letnika', $vpisna['sifra_letnika'])->lists('sifra_predmeta');
            for($i=0; $i<count($modul); $i++) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $modul[$i];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
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
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>$modularni, 'tips'=>1])->withErrors("Nimate dovolj KT!");
            }else{
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>1])->withErrors("Nimate dovolj KT!");
            }
        }


        Zeton::where('vpisna_stevilka', $vpisna["vpisna"])->where('sifra_studijskega_leta', $vpisna['sifra_studijskega_leta'])->update(['zeton_porabljen'=>1]);
        return redirect('home')->with('message', 'Vpisni list je oddan!');
    }


    public function izberi2($vp){
        $list = Input::all();
        $vpisna['vpisna'] = substr($vp, 0,8);
        $vpisna['sifra_studijskega_leta'] = substr($vp, 8,2);
        $vpisna['sifra_studijskega_programa'] = substr($vp, 10,7);
        $vpisna['sifra_letnika'] = substr($vp, 17,1);
        $vpisna['zeton'] = substr($vp, 18,1);

        Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_studijskega_leta', $vpisna['sifra_studijskega_leta'])->delete();

        $obvezni = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', NULL)->lists('sifra_predmeta');
        $obvezni_predmeti = [];
        $sum = 0;
        if(!Vpisan_predmet::where('vpisna_stevilka', $vpisna['vpisna'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
        where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_studijskega_leta', $vpisna['sifra_studijskega_leta'])->count()) {
            for($i=0; $i<count($obvezni); $i++){
                $obvezni_predmeti[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $obvezni[$i])->
                pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT')];
                $vpisi = new Vpisan_predmet();
                $vpisi->vpisna_stevilka = $vpisna['vpisna'];
                $vpisi->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $vpisi->sifra_predmeta = $obvezni[$i];
                $vpisi->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $vpisi->sifra_letnika = $vpisna['sifra_letnika'];
                $vpisi->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $vpisi->save();

                $sum += Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT');
            }
        }else{
            for($i=0; $i<count($obvezni); $i++){
                $obvezni_predmeti[$i] = [Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('priimek_profesorja'), Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta',$obvezni[$i])->
                pluck('sifra_profesorja'))->pluck('ime_profesorja'), Predmet::where('sifra_predmeta', $obvezni[$i])->
                pluck('naziv_predmeta'), Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT')];
                $sum += Predmet::where('sifra_predmeta', $obvezni[$i])->pluck('stevilo_KT');
            }
        }

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

        $stp = Studijski_program::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->pluck('naziv_studijskega_programa');

        if(array_key_exists('modul', $list) && array_key_exists('modul2', $list))
            if($list['modul2'] == $list['modul'])
                return view('predmeti', ['studijski_program' => $stp, 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips'=>0])->withErrors("Izberite različne module!");
            elseif($list['modul2'] == 0 || $list['modul'] == 0)
                return view('predmeti', ['studijski_program' => $stp, 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips'=>0])->withErrors("Izberite modul!");

        if(array_key_exists('prosti', $list) && array_key_exists('prosti2', $list)) {
            if ($list['prosti'] == $list['prosti2'] && $list['prosti'] != 0) {
                if ($vpisna['sifra_letnika'] == 3 && $vpisna['zeton'] == 1) {
                    return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => $modularni, 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
                } else {
                    return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
                }
            }
        }

        if(array_key_exists('strokovni', $list)) {
            if ($list['prosti'] == $list['strokovni'] && $list['prosti'] != 0) {
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
            }

            if ($list['prosti2'] == $list['strokovni'] && $list['prosti2'] != 0) {
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => [], 'tips' => 0])->withErrors("Izberite različne prosto izbirne predmete!");
            }
        }elseif($vpisna['sifra_letnika'] == 3 && $vpisna['zeton'] == 1){
            for($i=1; $i<=6; $i++){
                if ($modularni[$list['modularni' . $i]] == $prosti[$list['prosti']] && $list['modularni' . $i] != 0) {
                    return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                        'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>$modularni, 'tips'=>0])->
                    withErrors("Izberite različne modularne predmete! ");
                }
            }
        }else{
            $module1 = $list['modul'];
            $module2 = $list['modul2'];
            if($list['modul'] == 6 || $list['modul']==7){
                $module1 = $list['modul']+2;
            }
            if($list['modul2'] == 6 || $list['modul2']==7){
                $module2 = $list['modul2']+2;
            }

            $mod1 = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', $module1)->lists('sifra_predmeta');
            $mod2 = Predmet_studijskega_programa::where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_letnika', $vpisna['sifra_letnika'])->where('sifra_sestavnega_dela', $module2)->lists('sifra_predmeta');

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti']])[0])->pluck('sifra_predmeta'), $mod1)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                withErrors("Izberite različne modularne predmete! ");
            }

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti']])[0])->pluck('sifra_predmeta'), $mod2)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                withErrors("Izberite različne modularne predmete! ");
            }

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti2']])[0])->pluck('sifra_predmeta'), $mod1)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                withErrors("Izberite različne modularne predmete! ");
            }

            if(in_array(Predmet::where('naziv_predmeta',explode(" - ", $prosti[$list['prosti2']])[0])->pluck('sifra_predmeta'), $mod2)){
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>0])->
                withErrors("Izberite različne modularne predmete! ");
            }
        }

        if(array_key_exists('modularni' . 1, $list)){
            for($i=1; $i<=5; $i++){
                for($j=$i+1; $j<=6; $j++) {
                    if($list['modularni' . $i] != 0) {
                        if ($list['modularni' . $i] == $list['modularni' . $j]) {
                            return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                                'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => $modularni, 'tips' => 0])->
                            withErrors("Izberite različne modularne predmete! ");
                        }
                    }else{
                        return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                            'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni' => $modularni, 'tips' => 0])->
                        withErrors("Izberite modularne predmete! ");
                    }
                }
            }
            for($i=1; $i<=6; $i++) {
                if ($list['modularni' . $i] != 0) {
                    $pre = new Vpisan_predmet();
                    $pre->vpisna_stevilka = $vpisna['vpisna'];
                    $pre->sifra_predmeta = $modpredmeti[$list['modularni'.$i]-1];
                    $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                    $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                    $pre->sifra_letnika = $vpisna['sifra_letnika'];
                    $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                    $pre->save();
                }
            }
        }

        if(array_key_exists('prosti', $list)){
            if($list['prosti'] != 0) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $prosto_izbirni[$list['prosti'] - 1];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }
        }

        if(array_key_exists('prosti2', $list)){
            if($list['prosti2'] != 0) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $prosto_izbirni[$list['prosti2'] - 1];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }
        }

        if(array_key_exists('strokovni', $list)){
            if($list['strokovni'] != 0) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $strokovno_izbirni[$list['strokovni'] - 1];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }else{
                return view('predmeti', ['studijski_program' => $stp, 'predmeti'=>$obvezni_predmeti, 'sum' => $sum,
                    'prosti'=>$prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>1])->withErrors("Izberite en strokovni predmet!");
            }
        }

        if(array_key_exists('modul', $list)){
            if($list['modul']==6 || $list['modul']==7){
                $list['modul'] = $list['modul']+2;
            }

            $modul = Predmet_studijskega_programa::where('sifra_sestavnega_dela', $list['modul'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->
            where('sifra_letnika', $vpisna['sifra_letnika'])->lists('sifra_predmeta');
            for($i=0; $i<count($modul); $i++) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $modul[$i];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
            }
        }

        if(array_key_exists('modul2', $list)){
            if($list['modul2']==6 || $list['modul2']==7){
                $list['modul2'] = $list['modul2']+2;
            }

            $modul = Predmet_studijskega_programa::where('sifra_sestavnega_dela', $list['modul2'])->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->where('sifra_letnika', $vpisna['sifra_letnika'])->lists('sifra_predmeta');
            for($i=0; $i<count($modul); $i++) {
                $pre = new Vpisan_predmet();
                $pre->vpisna_stevilka = $vpisna['vpisna'];
                $pre->sifra_predmeta = $modul[$i];
                $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
                $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
                $pre->sifra_letnika = $vpisna['sifra_letnika'];
                $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
                $pre->save();
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
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>$modularni, 'tips'=>1])->withErrors("Nimate dovolj KT!");
            }else{
                return view('predmeti', ['studijski_program' => $stp, 'predmeti' => $obvezni_predmeti, 'sum' => $sum,
                    'prosti' => $prosti, 'strokovni' => $strokovni, 'moduli' => $moduli, 'vpisna' => $vp, 'modularni'=>[], 'tips'=>1])->withErrors("Nimate dovolj KT!");
            }
        }

        return redirect('home')->with('message', 'Vpisni list je oddan!');
    }


}
