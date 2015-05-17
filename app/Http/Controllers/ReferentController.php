<?php namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;
use Request;

use \App\Kandidat;
use \App\User;
use Illuminate\Database\QueryException;

use App\Zeton;
use App\Studijsko_leto;
use App\Letnik;
use App\Studijski_program;
use App\Oblika_studija;
use App\Nacin_studija;
use App\Vrsta_vpisa;

use Illuminate\Support\Facades\Input;

class ReferentController extends Controller {

    public function izberi()
    {
        $uvozeno = '';

        return view('referent.uvoz_podatkov', compact('uvozeno'));
    }

    public function uvozi()
    {

        $uvozeno = 'Kandidati, ki so bili uspešlo uvoženi:<br/>';
        $datoteka = Request::file('datoteka');

        $myfile = fopen($datoteka, "r") or die("Unable to open file!");
        try {
            if ($myfile) {
                while (($line = fgets($myfile)) !== false) {
                    $newline = explode(',', preg_replace('/\s+/', ',', trim($line)));
                    $kandidat = new Kandidat;
                    $kandidat->ime_kandidata = $newline[0];
                    $kandidat->priimek_kandidata = $newline[1];
                    $kandidat->sifra_studijskega_programa = substr($newline[2], 0, 7);
                    $kandidat->email_kandidata = substr($newline[2], 7);
                    $kandidat->save();

                    $user = new User;
                    $user->name = $kandidat->ime_kandidata;
                    $user->email = $kandidat->email_kandidata;
                    $user->password = bcrypt($kandidat->ime_kandidata);
                    $user->type = 0;
                    $user->save();
                    $uvozeno .= $kandidat->ime_kandidata . ' ' . $kandidat->priimek_kandidata . '<br/>';
                }

                fclose($myfile);
                $uvozeno .= '<br/>';
            }
        } catch (QueryException $e) {
            $uvozeno .= '<br/><br/>
                        <div class="alert alert-danger">
                            <ul>
                                Prišlo je do napake, zato ostali kandidati niso bili vnešeni.
                            </ul>
                        </div>';
        }

        return view('referent.uvoz_podatkov', compact('uvozeno'));
    }

    public function dodajZeton($vp)
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

        $oblike = Oblika_studija::get();
        $oblike_studija = [];
        for ($i = 0; $i < count($oblike); $i++) {
            $oblike_studija[$i] = $oblike[$i]->sifra_oblike_studija . " " . $oblike[$i]->opis_oblike_studija;
        }
        array_unshift($oblike_studija, "");

        $nacini = Nacin_studija::get();
        $nacini_studija = [];
        for ($i = 0; $i < count($nacini); $i++) {
            $nacini_studija[$i] = $nacini[$i]->sifra_nacina_studija. " " . $nacini[$i]->opis_nacina_studija;
        }
        array_unshift($nacini_studija, "");

        $vrste = Vrsta_vpisa::get();
        $vrste_vpisa = [];
        for ($i = 0; $i < count($vrste); $i++) {
            $vrste_vpisa[$i] = $vrste[$i]->sifra_vrste_vpisa . " " . $vrste[$i]->opis_vrste_vpisa;
        }
        array_unshift($vrste_vpisa, "");

        $prosta_izbira = [];
        $prosta_izbira[0] = 'NE';
        $prosta_izbira[1] = 'DA';

        //DODAJ ŽETON
        if(Input::get('dodaj')) {
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
            $oblikaStudija2 = Input::get( 'oblikaStudija' );
            $oblikaStudija;
            if($oblikaStudija2 != null){
                $oblikaStudija = Oblika_studija::where('sifra_oblike_studija', $oblike_studija[$oblikaStudija2])->pluck('sifra_oblike_studija');
            }
            $nacinStudija2 = Input::get( 'nacinStudija' );
            $nacinStudija;
            if($nacinStudija2 != null){
                $nacinStudija = Nacin_studija::where('sifra_nacina_studija', $nacini_studija[$nacinStudija2])->pluck('sifra_nacina_studija');
            }
            $vrstaVpisa2 = Input::get( 'vrstaVpisa' );
            $vrstaVpisa;
            if($vrstaVpisa2 != null){
                $vrstaVpisa = Vrsta_vpisa::where('sifra_vrste_vpisa', $vrste_vpisa[$vrstaVpisa2])->pluck('sifra_vrste_vpisa');
            }
            $prostaIzbira = Input::get( 'prostaIzbira' );

            /*if($stleto2 == null){
                echo 'blabla';
            }*/


            //echo $stprogram;

            if($stleto2 != null && $stletnik2 != null && $stprogram2 != null && $oblikaStudija2 != null && $nacinStudija2 != null && $vrstaVpisa2 != null && $prostaIzbira != null) {
                $novZeton = Zeton::create(['vpisna_stevilka' => $vp, 'sifra_studijskega_leta' => $stleto, 'sifra_letnika' => $stletnik, 'sifra_oblike_studija' => $oblikaStudija, 'sifra_nacina_studija' => $nacinStudija, 'sifra_vrste_vpisa' => $vrstaVpisa, 'zeton_porabljen' => 0, 'prosta_izbira_predmetov' => $prostaIzbira, 'sifra_studijskega_programa' => $stprogram]);
                $novZeton->save();
            }
            /*if($stleto2 != null && $stletnik != null && $oblikaStudija != null && $nacinStudija != null && $vrstaVpisa != null && $prostaIzbira != null) {
                $novZeton = Zeton::create(['vpisna_stevilka' => $vp, 'sifra_studijskega_leta' => $stleto, 'sifra_letnika' => $stletnik, 'sifra_oblike_studija' => $oblikaStudija, 'sifra_nacina_studija' => $nacinStudija, 'sifra_vrste_vpisa' => $vrstaVpisa, 'zeton_porabljen' => 0, 'prosta_izbira_predmetov' => $prostaIzbira]);
                $novZeton->save();
            }*/
        }


        //ODSTRANI ŽETON
        if(Input::get('odstrani')) {
            $stleto2 = Input::get( 'stleto' );
            $stleto;
            if($stleto2 != null){
                $stleto = Studijsko_leto::where('stevilka_studijskega_leta', $leto[$stleto2])->pluck('sifra_studijskega_leta');
            }
            $stprogram2 = Input::get( 'stprogram' );
            $stprogram;
            if($stprogram2 != null){
                $stprogram = Studijski_program::where('sifra_studijskega_programa', $studijski_programi[$stprogram2])->pluck('sifra_studijskega_programa');
            }

            if($stleto2 != null && $stprogram2 != null) {
                Zeton::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $stleto)->where('sifra_studijskega_programa', $stprogram)->delete();
                //Zeton::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $stleto)->where('sifra_studijskega_programa', $stprogram)->update(['sifra_letnika'=>$stletnik, 'sifra_oblike_studija'=>$oblikaStudija, 'sifra_nacina_studija'=>$nacinStudija, 'sifra_vrste_vpisa'=>$vrstaVpisa, 'prosta_izbira_predmetov'=>$prostaIzbira]);
            }
        }


        //UREDI ŽETON
        if(Input::get('uredi')) {
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
            $oblikaStudija2 = Input::get( 'oblikaStudija' );
            $oblikaStudija;
            if($oblikaStudija2 != null){
                $oblikaStudija = Oblika_studija::where('sifra_oblike_studija', $oblike_studija[$oblikaStudija2])->pluck('sifra_oblike_studija');
            }
            $nacinStudija2 = Input::get( 'nacinStudija' );
            $nacinStudija;
            if($nacinStudija2 != null){
                $nacinStudija = Nacin_studija::where('sifra_nacina_studija', $nacini_studija[$nacinStudija2])->pluck('sifra_nacina_studija');
            }
            $vrstaVpisa2 = Input::get( 'vrstaVpisa' );
            $vrstaVpisa;
            if($vrstaVpisa2 != null){
                $vrstaVpisa = Vrsta_vpisa::where('sifra_vrste_vpisa', $vrste_vpisa[$vrstaVpisa2])->pluck('sifra_vrste_vpisa');
            }
            $prostaIzbira = Input::get( 'prostaIzbira' );
            $idzetona = Input::get( 'idzetona' );

            if($stleto2 != null && $stletnik2 != null && $stprogram2 != null && $oblikaStudija2 != null && $nacinStudija2 != null && $vrstaVpisa2 != null && $prostaIzbira != null) {
                //Zeton::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $stleto)->where('sifra_letnika', $stletnik)->where('sifra_studijskega_programa', $stprogram)->update(['sifra_studijskega_leta'=>$stleto, 'sifra_letnika'=>$stletnik, 'sifra_studijskega_programa'=>$stprogram, 'sifra_oblike_studija'=>$oblikaStudija, 'sifra_nacina_studija'=>$nacinStudija, 'sifra_vrste_vpisa'=>$vrstaVpisa, 'prosta_izbira_predmetov'=>$prostaIzbira]);
                Zeton::where('id', $idzetona)->update(['sifra_studijskega_leta'=>$stleto, 'sifra_letnika'=>$stletnik, 'sifra_studijskega_programa'=>$stprogram, 'sifra_oblike_studija'=>$oblikaStudija, 'sifra_nacina_studija'=>$nacinStudija, 'sifra_vrste_vpisa'=>$vrstaVpisa, 'prosta_izbira_predmetov'=>$prostaIzbira]);
            }
        }


        //PREBERI VSE PORABLJENE IN NEPORABLJENE ŽETONE
        $vsiZetoni = Zeton::where('vpisna_stevilka', $vp)->get();
        $porabljeniZetoni = [];
        $neporabljeniZetoni = [];
        $j=0;
        for ($i = 0; $i < count($vsiZetoni); $i++) {
            if($vsiZetoni[$i]->zeton_porabljen == 1) {
                $porabljeniZetoni[$j][0] = Studijsko_leto::find($vsiZetoni[$i]->sifra_studijskega_leta)->stevilka_studijskega_leta;
                $porabljeniZetoni[$j][1] = Letnik::find($vsiZetoni[$i]->sifra_letnika)->stevilka_letnika;
                $porabljeniZetoni[$i][2] = Studijski_program::find($vsiZetoni[$i]->sifra_studijskega_programa)->naziv_studijskega_programa;
                //$porabljeniZetoni[$j][2] = 0;  // ker še ni baza posodobljena
                $porabljeniZetoni[$j][3] = Oblika_studija::find($vsiZetoni[$i]->sifra_oblike_studija)->opis_oblike_studija;
                $porabljeniZetoni[$j][4] = Nacin_studija::find($vsiZetoni[$i]->sifra_nacina_studija)->opis_nacina_studija;
                $porabljeniZetoni[$j][5] = Vrsta_vpisa::find($vsiZetoni[$i]->sifra_vrste_vpisa)->opis_vrste_vpisa;
                $porabljeniZetoni[$j][6] = 'NE';
                if($vsiZetoni[$i]->prosta_izbira_predmetov == 1) {$porabljeniZetoni[$j][6] = 'DA';}
                $porabljeniZetoni[$j][7] = $vsiZetoni[$i]->id;
                $j++;
            }
        }
        $j=0;
        for ($i = 0; $i < count($vsiZetoni); $i++) {
            if($vsiZetoni[$i]->zeton_porabljen == 0) {
                $neporabljeniZetoni[$j][0] = array_search(Studijsko_leto::find($vsiZetoni[$i]->sifra_studijskega_leta)->stevilka_studijskega_leta,$leto);
                //$neporabljeniZetoni[$j][0] = $vsiZetoni[$i]->stevilka_studijskega_leta;
                $neporabljeniZetoni[$j][1] = array_search(Letnik::find($vsiZetoni[$i]->sifra_letnika)->stevilka_letnika,$letnik);
                $neporabljeniZetoni[$j][2] = array_search($vsiZetoni[$i]->sifra_studijskega_programa . ' ' . Studijski_program::find($vsiZetoni[$i]->sifra_studijskega_programa)->naziv_studijskega_programa,$studijski_programi);
                //$neporabljeniZetoni[$j][2] = 0;  // ker še ni baza posodobljena
                $neporabljeniZetoni[$j][3] = array_search($vsiZetoni[$i]->sifra_oblike_studija . ' ' . Oblika_studija::find($vsiZetoni[$i]->sifra_oblike_studija)->opis_oblike_studija,$oblike_studija);
                $neporabljeniZetoni[$j][4] = array_search($vsiZetoni[$i]->sifra_nacina_studija . ' ' . Nacin_studija::find($vsiZetoni[$i]->sifra_nacina_studija)->opis_nacina_studija,$nacini_studija);
                $neporabljeniZetoni[$j][5] = array_search($vsiZetoni[$i]->sifra_vrste_vpisa . ' ' . Vrsta_vpisa::find($vsiZetoni[$i]->sifra_vrste_vpisa)->opis_vrste_vpisa,$vrste_vpisa);
                $neporabljeniZetoni[$j][6] = 0;
                if($vsiZetoni[$i]->prosta_izbira_predmetov == 1) {$neporabljeniZetoni[$j][6] = 1;}
                $neporabljeniZetoni[$j][7] = $vsiZetoni[$i]->id;
                $j++;
            }
        }

        return view('zeton', ['vp' => $vp, 'leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi, 'oblika' => $oblike_studija, 'nacin' => $nacini_studija, 'vrsta' => $vrste_vpisa, 'izbira' => $prosta_izbira, 'porabljeniZetoni' => $porabljeniZetoni, 'neporabljeniZetoni' => $neporabljeniZetoni]);
    }
}