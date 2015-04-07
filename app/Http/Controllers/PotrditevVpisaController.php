<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Student;
use App\Vpis;
use Illuminate\Http\Request;

class PotrditevVpisaController extends Controller {

    public function nepotrjeni()
    {
        $vpisi = Vpis::where('vpis_potrjen', 0)->lists('vpisna_stevilka');

        if (count($vpisi) > 0) {

            $studenti = [];
            for ($i = 0; $i < count($vpisi); $i++) {
                $studenti[$i] = Student::where('vpisna_stevilka', $vpisi[$i])->get();
            }

            return view('nepotrjeni')->with('studenti', $studenti);
        }else{
            echo "VSE JE POTRJENO";
        }
    }

	public function potrdi($vpisna){
        echo $vpisna;
    }

    public function natisni(){
        return view('izpisvpisnegalista');
    }

}
