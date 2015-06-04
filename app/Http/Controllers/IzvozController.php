<?php namespace App\Http\Controllers;

use App\Student;
use App\Vpis;
use App\Vpisan_predmet;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Vrsta_vpisa;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use PDF;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class IzvozController extends Controller {

	public function studentiPredmetaPdf(){

        $predmet = Input::get( 'predmet' );
        $program = Input::get( 'program' );
        $letnik = Input::get( 'letnik' );
        $leto = Input::get( 'leto' );
        $naslov = Input::get('naslov');
		$stHidden[0] = $predmet;
		$stHidden[1] = $program;
		$stHidden[2] = $letnik;
		$stHidden[3] = $leto;

        $predmeti = Vpisan_predmet::where('sifra_predmeta', $predmet)->where('sifra_studijskega_programa', $program)->where('sifra_letnika', $letnik)->
        where('sifra_studijskega_leta', $leto)->get();

        $studenti = [];
        for($i=0; $i<count($predmeti); $i++){
            $vp = $predmeti[$i]->vpisna_stevilka;
            $studenti[$i][0] = $vp;
            $studenti[$i][1] = Student::where('vpisna_stevilka', $vp)->pluck('ime_studenta')." ".Student::where('vpisna_stevilka', $vp)->pluck('priimek_studenta');
            $studenti[$i][2] = Vrsta_vpisa::where('sifra_vrste_vpisa', Vpis::where('vpisna_stevilka', $vp)->where('sifra_studijskega_leta', $leto)->
            where('sifra_studijskega_programa', $program)->pluck('sifra_vrste_vpisa'))->pluck('opis_vrste_vpisa');
        }
		$pdf = PDF::loadView('studentipredmetaexport', ['students' => $studenti, 'naslov' => $naslov, 'stHidden' => $stHidden, 'izvoz' => 1]);
		return $pdf->stream('seznamStudentov.pdf');
		
		//return view('studentipredmeta', ['students' => $studenti, 'naslov' => $naslov[0], 'stHidden' => $stHidden]);
		//return Redirect::back();
	}
	
	public function studentiPredmetaCsv(){
		// pretestiraj!
		$headers = [
				'Content-type'        => 'application/csv'
			,   'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
			,   'Content-type'        => 'text/csv'
			,   'Content-Disposition' => 'attachment; filename=galleries.csv'
			,   'Expires'             => '0'
			,   'Pragma'              => 'public'
		];
        $predmet = Input::get( 'predmet' );
        $program = Input::get( 'program' );
        $letnik = Input::get( 'letnik' );
        $leto = Input::get( 'leto' );
        $naslov = Input::get( 'naslov' );
		
		$studenti = Vpisan_predmet::where('Vpisan_predmet.sifra_predmeta', $predmet)
						->where('Vpisan_predmet.sifra_studijskega_programa', $program)
						->where('Vpisan_predmet.sifra_letnika', $letnik)
						->where('Vpisan_predmet.sifra_studijskega_leta_izvedbe_predmeta', $leto)
						->join('student', 'Vpisan_predmet.vpisna_stevilka', '=', 'student.vpisna_stevilka')
						->join('vpis', function($join)
							{
								$join->on('Vpisan_predmet.vpisna_stevilka', '=', 'Vpis.vpisna_stevilka')->on('Vpisan_predmet.sifra_studijskega_leta', '=', 'Vpis.sifra_studijskega_leta');
							})
						->join('Vrsta_vpisa', 'Vpis.sifra_vrste_vpisa', '=', 'Vrsta_vpisa.sifra_vrste_vpisa')
						->select('student.vpisna_stevilka AS vpisna', 'student.priimek_studenta AS priimek', 'student.ime_studenta AS ime', 'Vrsta_vpisa.opis_vrste_vpisa AS vrsta_vpisa')
						->orderByRaw('priimek COLLATE utf8_slovenian_ci')
						->get();

		# prva vrstica: imena stolpcev (preveri ce je izpis ok)
		array_unshift($studenti, array_keys($studenti[0]));

		$callback = function() use ($studenti) 
		{
			$outCsv = fopen('php://output', 'w');
			foreach ($studenti as $row) { 
				fputcsv($outCsv, $row);
			}
			fclose($outCsv);
		};

		return Response::stream($callback, 200, $headers);
	}

}