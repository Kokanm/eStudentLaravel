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

    public function uvozi()
    {
        $uvozeno = 'Kandidati so bili uspešlo uvoženi!<br/><br/>';
        $datoteka = Request::get('datoteka');

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

        $myfile = fopen($datoteka, "r") or die("Unable to open file!");
        for ($i = 0; $i < (filesize($datoteka)/127); $i++) {
            $kandidat = new Kandidat;
            $kandidat->ime_kandidata = trim(fread($myfile, 30));
            $kandidat->priimek_kandidata = trim(fread($myfile, 30));
            $kandidat->sifra_studijskega_programa = trim(fread($myfile, 7));
            $kandidat->email_kandidata = trim(fread($myfile, 60));
            $kandidat->save();

            $user = new User;
            $user->name = $kandidat->ime_kandidata;
            $user->email = $kandidat->email_kandidata;
            //$user->password = Hash::make('neki');
            $user->password = bcrypt($kandidat->ime_kandidata);
            $user->type = 0;
            $user->save();
        }
        fclose($myfile);

        return view('referent.uvoz_podatkov', compact('uvozeno'));
    }

}
