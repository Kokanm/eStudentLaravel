<?php namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;
use Request;

use \App\Kandidat;
use \App\User;



class ReferentController extends Controller {

    public function izberi()
    {
        $uvozeno = '';

        return view('referent.uvoz_podatkov', compact('uvozeno'));
    }

    //public function uvozi($request, Exception $e)
    public function uvozi()
    {
        $uvozeno = 'Kandidati, ki so bili uspešlo uvoženi:<br/>';
        //$datoteka = Request::get('datoteka');
        $datoteka = Request::file('datoteka');

        /*$myfile = fopen($datoteka, "r") or die("Unable to open file!");
        $prebrano = fread($myfile,filesize($datoteka));
        fclose($myfile);
        $uvozeno = $prebrano;*/

        /*$myfile = fopen($datoteka, "r") or die("Unable to open file!");
        for ($i = 0; $i < (filesize($datoteka)/127); $i++) {
            $studentArray[$i][0] = trim(fread($myfile, 30));	// ime
            $studentArray[$i][1] = trim(fread($myfile, 30));	// priimek
            $studentArray[$i][2] = trim(fread($myfile, 7));		// program
            $studentArray[$i][3] = trim(fread($myfile, 60));	// email
        }
        fclose($myfile);
        $uvozeno = $studentArray[0][0];*/

        /*if ($e) {
            $uvozeno = 'Prišlo je do napake!<br/><br/>';
            return view('referent.uvoz_podatkov', compact('uvozeno'));
        }*/
        $myfile = fopen($datoteka, "r") or die("Unable to open file!");
        //for ($i = 0; $i < (filesize($datoteka)/128); $i++) {
        for ($i = 0; $i < (filesize($datoteka)/129); $i++) {
            $kandidat = new Kandidat;
            $kandidat->ime_kandidata = trim(fread($myfile, 30));
            $kandidat->priimek_kandidata = trim(fread($myfile, 30));
            $kandidat->sifra_studijskega_programa = trim(fread($myfile, 7));
            $kandidat->email_kandidata = trim(fread($myfile, 60));
            fread($myfile, 2);  //nova vrstica cr lf
            //fread($myfile, 1);  //če je za novo vrstico samo en znak, potem preberi en znak in popravi tudi for zanko
            $kandidat->save();

            $user = new User;
            $user->name = $kandidat->ime_kandidata;
            $user->email = $kandidat->email_kandidata;
            //$user->password = Hash::make('neki');
            $user->password = bcrypt($kandidat->ime_kandidata);
            $user->type = 0;
            $user->save();
            $uvozeno .= $kandidat->ime_kandidata . ' ' . $kandidat->priimek_kandidata . '<br/>';
        }
        fclose($myfile);
        $uvozeno .= '<br/>';

        return view('referent.uvoz_podatkov', compact('uvozeno'));
    }

}
