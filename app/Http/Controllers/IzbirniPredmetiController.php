<?php namespace App\Http\Controllers;

use App\Predmet_studijskega_programa;
use App\Predmet;
use App\Vpisan_predmet;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class IzbirniPredmetiController extends Controller {

	public function izberi($vp){
        $list = Input::all();
        $vpisna['vpisna'] = substr($vp, 0,8);
        $vpisna['sifra_studijskega_leta'] = substr($vp, 8,2);
        $vpisna['sifra_studijskega_programa'] = substr($vp, 10,7);
        $vpisna['sifra_letnika'] = substr($vp, 17,1);

        $prosto_izbirni = Predmet_studijskega_programa::where('sifra_sestavnega_dela', '7')->lists('sifra_predmeta');
        $prosti = [];
        for ($i=0; $i < count($prosto_izbirni); $i++){
            $prosti[$i] = Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $prosto_izbirni[$i])->
                pluck('stevilo_KT')." KT";
        }

        $strokovno_izbirni = Predmet_studijskega_programa::where('sifra_sestavnega_dela', '6')->lists('sifra_predmeta');
        $strokovni = [];
        for ($i=0; $i < count($strokovno_izbirni); $i++){
            $strokovni[$i] = Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->pluck('naziv_predmeta'). " - ".Predmet::where('sifra_predmeta', $strokovno_izbirni[$i])->
                pluck('stevilo_KT')." KT";
        }

        if(array_key_exists('prosti', $list)){
            $pre = new Vpisan_predmet();
            $pre->vpisna_stevilka = $vpisna['vpisna'];
            $pre->sifra_predmeta = $prosto_izbirni[$list['prosti']];
            $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
            $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
            $pre->sifra_letnika = $vpisna['sifra_letnika'];
            $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
            $pre->save();
        }

        if(array_key_exists('prosti2', $list)){
            $pre = new Vpisan_predmet();
            $pre->vpisna_stevilka = $vpisna['vpisna'];
            $pre->sifra_predmeta = $prosto_izbirni[$list['prosti2']];
            $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
            $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
            $pre->sifra_letnika = $vpisna['sifra_letnika'];
            $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
            $pre->save();
        }

        if(array_key_exists('strokovni', $list)){
            $pre = new Vpisan_predmet();
            $pre->vpisna_stevilka = $vpisna['vpisna'];
            $pre->sifra_predmeta = $prosto_izbirni[$list['strokovni']];
            $pre->sifra_studijskega_leta = $vpisna['sifra_studijskega_leta'];
            $pre->sifra_studijskega_programa = $vpisna['sifra_studijskega_programa'];
            $pre->sifra_letnika = $vpisna['sifra_letnika'];
            $pre->sifra_studijskega_leta_izvedbe_predmeta = $vpisna['sifra_studijskega_leta'];
            $pre->save();
        }

        if(array_key_exists('modul', $list)){
            if($list['modul']==5 || $list['modul']==6){
                $list['modul']+2;
            }

            $modul = Predmet_studijskega_programa::where('sifra_sestavnega_dela', $list['modul'] + 1)->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->where('sifra_letnika', $vpisna['sifra_letnika'])->lists('sifra_predmeta');
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
            if($list['modul2']==5 || $list['modul']==6){
                $list['modul2']+2;
            }

            $modul = Predmet_studijskega_programa::where('sifra_sestavnega_dela', $list['modul2'] + 1)->where('sifra_studijskega_programa', $vpisna['sifra_studijskega_programa'])->where('sifra_letnika', $vpisna['sifra_letnika'])->lists('sifra_predmeta');
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

    }

}
