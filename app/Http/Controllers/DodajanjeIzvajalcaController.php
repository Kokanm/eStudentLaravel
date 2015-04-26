<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Letnik;
use App\Studijsko_leto;
use App\Studijski_program;
use Illuminate\Http\Request;

class DodajanjeIzvajalcaController extends Controller {

    public function lista()
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
        }
        array_unshift($letnik, "");

        $programi = Studijski_program::get();
        $studijski_programi = [];
        for ($i = 0; $i < count($programi); $i++) {
            $studijski_programi[$i] = $programi[$i]->sifra_studijskega_programa . " " . $programi[$i]->naziv_studijskega_programa;
        }
        array_unshift($studijski_programi, "");

        return view('dodajizvajalca', ['leto' => $leto, 'letnik' => $letnik, 'program' => $studijski_programi]);
    }

    public function dodaj()
    {

    }

}
