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
use App\Vpis;
use App\Vpisan_predmet;
use App\Vrsta_vpisa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class SeznamStudentovPredmetaController extends Controller {

    public function izberi1(){
        $url="predmet";
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

        return view('izberipredmet', ['predmeti' => $predmeti , 'id_leto' => $id_leto, 'url' => "SeznamStudentovPredmetaController" ]);

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

        return view('izberipredmet', ['predmeti' => $predmeti , 'id_leto' => $id_leto,  'url' => "SeznamStudentovPredmetaController"]);
    }

    public function cmp($a, $b)
    {
        return strcmp($a->priimek_studenta, $b->priimek_studenta);
    }

    public function izpisi($premet){
        $id_leto = Input::get('id_leto');
        $info = Vpisan_predmet::where('sifra_predmeta', $premet)->where('sifra_studijskega_leta', $id_leto)->lists('vpisna_stevilka');
        $leto = Studijsko_leto::where('sifra_studijskega_leta', $id_leto)->pluck('stevilka_studijskega_leta');

        $profesorDATA= Profesor::where('sifra_profesorja', Izvedba_predmeta::where('sifra_predmeta', $premet)->where('sifra_studijskega_leta', $id_leto)->pluck('sifra_profesorja'))->get()[0];
        $profesor= $profesorDATA->ime_profesorja . " " . $profesorDATA->priimek_profesorja;
        $ime_predmet=Predmet::where('sifra_predmeta', $premet)->pluck('naziv_predmeta');

        $studenti=[];
        for ($i=0; $i< count($info); $i++){
            if(Vpis::where('vpisna_stevilka', $info[$i])->where('sifra_studijskega_leta', $leto)->where('vpis_potrjen', 1)->first())
                $studenti[$i]=Student::where('vpisna_stevilka', $info[$i])->first();
        }
        usort($studenti,  array($this, "cmp"));

        $vrsta = [];
        $vpisni = [];
        for($i=0; $i<count($studenti); $i++){
            $vrsta[$i] = Vrsta_vpisa::where('sifra_vrste_vpisa', Vpis::where('vpisna_stevilka', $studenti[$i]->vpisna_stevilka)->where('sifra_studijskega_leta', $id_leto)->
            pluck('sifra_vrste_vpisa'))->pluck('opis_vrste_vpisa');
            $vpisni[$i] = $studenti[$i]->vpisna_stevilka;
        }

        $view=view('seznamstudentovnapredmet', ['sifra_predmeta' => $premet, 'vrsta'=>$vrsta, 'vpisni'=>$vpisni, 'stlet'=>$leto, 'ime_predmet' => $ime_predmet ,  'profesor' => $profesor,  'student' => $studenti ,'html' => ""])->renderSections()['content'];
        return view('seznamstudentovnapredmet', ['sifra_predmeta' => $premet, 'vrsta'=>$vrsta, 'vpisni'=>$vpisni,'stlet'=>$leto, 'ime_predmet' => $ime_predmet ,   'profesor' => $profesor,  'student' => $studenti , 'html' => $view]);
    }
}
