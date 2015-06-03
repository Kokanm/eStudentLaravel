<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 25/05/2015
 * Time: 22:22
 */
use App\Izpit;
use App\Izvedba_predmeta;
use App\Profesor;
use App\Studijsko_leto;
use App\Predmet;
use App\Predmet_studijskega_programa;
use App\Izpitni_rok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class IzpisiRezultatiController extends Controller
{

    public function izberi1(){
        $leto=Studijsko_leto::lists('stevilka_studijskega_leta');
        return view('izpisrezultati', ['let' => $leto, 'tip' => Auth::user()->type]);
    }

    public function izberiProf(){

        $user_email=Auth::user()->email;

        $chose_id_let = Input::get('st_let');
        $id_leto = Studijsko_leto::all()->get($chose_id_let)->sifra_studijskega_leta;

        $id_profesor=Profesor::where('email_profesorja',$user_email)->first()->sifra_profesorja;
        $predmetDATA=Izvedba_predmeta::where('sifra_profesorja',$id_profesor)->where('sifra_studijskega_leta',$id_leto)->get();
        $predmeti=[];
        for ( $i=0; $i < count($predmetDATA); $i++ )
        {
            $predmeti[$i] = Predmet::where('sifra_predmeta',$predmetDATA[$i]->sifra_predmeta)->first();
        }
        //d($predmeti);
        return view('listapredmeti', ['predmeti' => $predmeti , 'id_leto' => $id_leto]);

    }

    public function izberi2(){
        $keyword = Input::get('keyword');
        $chose_id_let = Input::get('st_let');
        $id_leto = Studijsko_leto::all()->get($chose_id_let)->sifra_studijskega_leta;
        if (count(explode(" ", $keyword)) > 1) {
            $predmeti = Predmet::where('naziv_predmeta', 'LIKE', explode(" ", $keyword)[1] . '%')->orWhere('sifra_predmeta', 'LIKE', explode(" ", $keyword)[1] . '%')->get();
        } else {
            $predmeti =Predmet::where('naziv_predmeta', 'LIKE', $keyword . '%')->orWhere('sifra_predmeta', 'LIKE', $keyword . '%')->get();
        }
        //dd($predmeti);
        return view('listapredmeti', ['predmeti' => $predmeti , 'id_leto' => $id_leto]);
    }

    public function izberi3($predmet){
        $id_leto=Input::get('id_leto');
        $izpitni_rok= Izpitni_rok::where('sifra_predmeta', $predmet)->where('sifra_studijskega_leta', $id_leto)->get();
        $format_rok= [];
        for ($i=0; $i< count($izpitni_rok); $i++)
        {
            $profesorDATA=Profesor::where('sifra_profesorja', $izpitni_rok[$i]->sifra_profesorja)->get()[0];
            $profesor= $profesorDATA->ime_profesorja . " " . $profesorDATA->priimek_profesorja;
            $ime_predmet=Predmet::where('sifra_predmeta', $predmet)->pluck('naziv_predmeta');

            $format_rok[$i][0]=$ime_predmet;
            $format_rok[$i][1]=$predmet;
            $format_rok[$i][2]=$izpitni_rok[$i]->sifra_letnika;
            $format_rok[$i][3]=$profesor;
            $format_rok[$i][4]= $izpitni_rok[$i]->datum;
            $format_rok[$i][5]=$izpitni_rok[$i];
            //date("d.m.Y", strtotime($izpitni_rok[$i]->datum))

        }
        //dd($format_rok);

        return view('listaizpitnerok', ['rok' => $format_rok]);
    }

    public function izpisi($premet,$datum){
        $info=Input::get('row');

        $profesorDATA=Profesor::where('sifra_profesorja', explode("-",$info)[0])->first();
        $profesor= $profesorDATA->ime_profesorja . " " . $profesorDATA->priimek_profesorja;
        $ime_predmet=Predmet::where('sifra_predmeta', $premet)->pluck('naziv_predmeta');

        $prostor=explode("-",$info)[1];
        $ura=explode("-",$info)[2];

        $rezultati=Izpit::where('sifra_predmeta',$premet)->where('datum',$datum)->get();
        $polaganje=[];
        for ($i=0; $i< count($rezultati); $i++)
        {
        $polaganje[$i]=Izpit::where('sifra_predmeta',$premet)->where('vpisna_stevilka', $rezultati[$i]->vpisna_stevilka)->where('ocena','>',0)->where('datum','<',$datum)->count();
        }
        //dd($polaganje);
        //echo $rezultati;
        return view('rezultatipisniizpit', ['rez' => $rezultati, 'sifra_predmeta' => $premet, 'ime_predmet' => $ime_predmet , 'datum' => $datum, 'ura' => $ura, 'prostor' => $prostor, 'profesor' => $profesor, 'polaganje' => $polaganje  ]);
    }
}