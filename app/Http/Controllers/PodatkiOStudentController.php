<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Student;
use App\Sklep;
class PodatkiOStudentController extends Controller {
    public function izpisStudent($vs){
        $student = Student::where('vpisna_stevilka', $vs)->get()[0];
        $ime=$student->ime_studenta;
        $priimek=$student->priimek_studenta;
        $tel=$student->prenosni_telefon;
        $email=$student->email_studenta;
        $rojstni=date('d.m.Y',strtotime($student->datum_rojstva));
        $sklep= Sklep::where('vpisna_stevilka', $vs)->get();
        $tmp=$sklep;
        return view('izpisstudent', ['vpisna'=>$vs ,'ime'=>$ime,'priimek'=>$priimek,'email'=>$email,'tel'=>$tel,'rojstni'=>$rojstni, 'tmp'=>$tmp, 'sklep'=>$sklep]);
    }
}