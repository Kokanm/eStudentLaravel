<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Izpit;
use App\Izvedba_predmeta;
use App\Profesor;
use App\Student;
use App\Studijsko_leto;
use App\Predmet;
use App\Predmet_studijskega_programa;
use App\Izpitni_rok;
use App\Vpisan_predmet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class OceneIzpitController extends Controller {

    public function izberi1(){
        $url="ocene";
        $leto=Studijsko_leto::lists('stevilka_studijskega_leta');
        return view('izpisrezultati', ['let' => $leto, 'tip' => Auth::user()->type, 'url' => $url]);
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
        return view('listapredmeti', ['predmeti' => $predmeti , 'id_leto' => $id_leto, 'url' => "OceneIzpitController" ]);

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
        return view('listapredmeti', ['predmeti' => $predmeti , 'id_leto' => $id_leto,  'url' => "OceneIzpitController"]);
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

        return view('listaizpitnerok', ['rok' => $format_rok, 'url' => "OceneIzpitController"]);
    }

    public function cmp($a, $b)
    {
        return strcmp($a->priimek_studenta, $b->priimek_studenta);
    }

    public function izpisi($premet,$datum){
        $info=Input::get('row');
        $ime_sw=0;
        if( Input::get('ime') ){
            $ime_sw=1;
        }

        $profesorDATA=Profesor::where('sifra_profesorja', explode("-",$info)[0])->first();
        $profesor= $profesorDATA->ime_profesorja . " " . $profesorDATA->priimek_profesorja;
        $ime_predmet=Predmet::where('sifra_predmeta', $premet)->pluck('naziv_predmeta');

        $prostor=explode("-",$info)[1];
        $ura=explode("-",$info)[2];
        $stLet=explode("-",$info)[3];
        $studLeto=Studijsko_leto::where('sifra_studijskega_leta', $stLet)->first()->stevilka_studijskega_leta;

        $rezultatiRAW=Izpit::where('sifra_predmeta',$premet)->where('datum',$datum)->whereNotNull('ocena')->get();
        //KOKAN PEDER
        $studenti=[];
        for ($i=0; $i< count($rezultatiRAW); $i++){
            $studenti[$i]=Student::where('vpisna_stevilka', $rezultatiRAW[$i]->vpisna_stevilka)->first();
        }
        usort($studenti,  array($this, "cmp"));
        $rezultati=[];
        for ($i=0; $i< count($studenti); $i++){
            $rezultati[$i]=Izpit::where('sifra_predmeta',$premet)->where('datum',$datum)->where('vpisna_stevilka', $studenti[$i]->vpisna_stevilka)->first();
        }

        $polaganje=[];
        $polaganjeLetos=[];
        $stLetVpis=[];
        for ($i=0; $i< count($studenti); $i++)
        {
            $polaganje[$i]=Izpit::where('sifra_predmeta',$premet)->where('vpisna_stevilka', $studenti[$i]->vpisna_stevilka)->where('ocena','>',0)->where('datum','<',$datum)->count();
            $polaganjeLetos[$i]=Izpit::where('sifra_predmeta',$premet)->where('vpisna_stevilka', $studenti[$i]->vpisna_stevilka)->where('ocena','>',0)->where('datum','<',$datum)->where('sifra_studijskega_leta', $stLet)->count();
            $tmpLetVpis=Vpisan_predmet::where('sifra_predmeta',$premet)->where('vpisna_stevilka', $studenti[$i]->vpisna_stevilka)->orderBy('sifra_studijskega_leta')->first();
            $stLetVpis[$i]=Studijsko_leto::where('sifra_studijskega_leta', $tmpLetVpis->sifra_studijskega_leta)->first()->stevilka_studijskega_leta;
        }
        //dd($polaganje);
        //echo $rezultati;
        return view('oceneizpit', ['rez' => $rezultati, 'sifra_predmeta' => $premet, 'ime_predmet' => $ime_predmet , 'datum' => $datum, 'ura' => $ura, 'prostor' => $prostor, 'profesor' => $profesor, 'polaganje' => $polaganje, 'trig' => $ime_sw, 'student' => $studenti ,'stlet' => $studLeto, 'polaganjeLetos' => $polaganjeLetos, 'stLetVpis' => $stLetVpis ]);
    }

}
