<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Izpit;
use App\Izvedba_predmeta;
use App\Predmet;
use App\Profesor;
use App\Student;
use App\Sklep;
use App\Studijsko_leto;
use App\Vpisan_predmet;
use Illuminate\Support\Facades\Auth;

class PodatkiOStudentController extends Controller {
    public function izpisStudent($vs){

        //--- get profesor id
        $em_prof=Auth::user()->email;
        $id_prof= Profesor::where('email_profesorja', $em_prof)->first()->sifra_profesorja;

        $predmeti= Izvedba_predmeta::where('sifra_profesorja',$id_prof)
            ->orWhere('sifra_profesorja2',$id_prof)
            ->orWhere('sifra_profesorja3',$id_prof)
            ->orderBy('sifra_studijskega_leta')
            ->get();
        //dd($predmeti);
        //---------------

        $student = Student::where('vpisna_stevilka', $vs)->get()[0];
        $ime=$student->ime_studenta;
        $priimek=$student->priimek_studenta;
        $tel=$student->prenosni_telefon;
        $email=$student->email_studenta;
        $rojstni=date('d.m.Y',strtotime($student->datum_rojstva));
        $sklep= Sklep::where('vpisna_stevilka', $vs)->get();
        $tmp=$sklep;

        $vpisan_predmet=[];
        $count=0;
        for ($i=0 ;$i<count($predmeti);$i++ ){
            $tmp=Vpisan_predmet::where('vpisna_stevilka',$vs)
                ->where('sifra_predmeta', $predmeti[$i]->sifra_predmeta)
                ->where('sifra_studijskega_programa', $predmeti[$i]->sifra_studijskega_programa)
                ->where('sifra_letnika',$predmeti[$i]->sifra_letnika)
                ->where('sifra_studijskega_leta',$predmeti[$i]->sifra_studijskega_leta)
                ->first();
            if($tmp!=null){
                $tmp=$predmeti[$i];
                $vpisan_predmet[$count]=$tmp;
                $count++;

            }
            //$vpisan_predmet[$i]=$tmp;
        }
        //dd($vpisan_predmet);


        //$ime_predmet=[];
        $tabela_ocena=[];
        $count=0;

        // vpisan predmet = izvedba_predmet eloquent

        for ($i=0 ;$i<count($vpisan_predmet);$i++ ){
            $izpit=Izpit::where('sifra_predmeta',$vpisan_predmet[$i]->sifra_predmeta)
                ->where('vpisna_stevilka',$vs)
                ->where('sifra_studijskega_programa', $vpisan_predmet[$i]->sifra_studijskega_programa)
                ->where('sifra_studijskega_leta',$vpisan_predmet[$i]->sifra_studijskega_leta)
                ->whereNull('cas_odjave')
                ->get();


            //$ime_predmet[$i]=Predmet::where('sifra_predmeta',$vpisan_predmet[$i]->sifra_predmeta)->first()->naziv_predmeta;
            //echo(count($izpit));
            if(count($izpit)==0){
                $tabela_ocena[$count][0] = $i+1;
                $tabela_ocena[$count][1] = $vpisan_predmet[$i]->sifra_predmeta;
                $tabela_ocena[$count][2] = Predmet::where('sifra_predmeta', $vpisan_predmet[$i]->sifra_predmeta)->first()->naziv_predmeta;
                $tabela_ocena[$count][3] = Studijsko_leto::where('sifra_studijskega_leta',$vpisan_predmet[$i]->sifra_studijskega_leta)->first()->stevilka_studijskega_leta;
                $tabela_ocena[$count][4] = "";
                $tabela_ocena[$count][5] = "";
                $tabela_ocena[$count][6] = "";
                $tabela_ocena[$count][7] = "";
                $count++;

            }
            else {
                for ($j = 0; $j < count($izpit); $j++) {
                    if ($j == 0) {
                        $tabela_ocena[$count][0] = $i+1;
                        $tabela_ocena[$count][1] = $vpisan_predmet[$i]->sifra_predmeta;
                        $tabela_ocena[$count][2] = Predmet::where('sifra_predmeta', $vpisan_predmet[$i]->sifra_predmeta)->first()->naziv_predmeta;
                        $tabela_ocena[$count][3] = Studijsko_leto::where('sifra_studijskega_leta', $vpisan_predmet[$i]->sifra_studijskega_leta)->first()->stevilka_studijskega_leta;
                    }else{
                        $tabela_ocena[$count][0] = "";
                        $tabela_ocena[$count][1] = "";
                        $tabela_ocena[$count][2] = "";
                        $tabela_ocena[$count][3] = "";

                    }
                    $tabela_ocena[$count][4] = $vpisan_predmet[$i]->sifra_letnika . " letnik";
                    $tabela_ocena[$count][5] = $izpit[$j]->datum;
                    $tabela_ocena[$count][6] = $izpit[$j]->tocke_izpita;
                    $tabela_ocena[$count][7] =$izpit[$j]->ocena;

                    if( is_integer($tabela_ocena[$count][7]) && $tabela_ocena[$count][7]=='0' ){
                        $tabela_ocena[$count][7]="VP";
                    }

                    $count++;

                }
            }

        }
        //dd($tabela_ocena);



        return view('izpisstudent', ['vpisna'=>$vs ,'ime'=>$ime,'priimek'=>$priimek,'email'=>$email,'tel'=>$tel,'rojstni'=>$rojstni, 'tmp'=>$tmp, 'sklep'=>$sklep , 'ocene' =>$tabela_ocena]);
    }
}