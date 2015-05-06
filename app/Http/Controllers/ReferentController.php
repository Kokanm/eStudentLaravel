<?php namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;
use Request;

use \App\Kandidat;
use \App\User;
use Illuminate\Database\QueryException;

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

}