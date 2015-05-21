<?php namespace App\Http\Controllers;
use App\Student;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
class StudentController extends Controller
{
    public function search()
    {
        $keyword = Input::get('keyword');
        if (count(explode(" ", $keyword)) > 1) {
            $students = Student::where('ime_studenta', 'LIKE', explode(" ", $keyword)[1] . '%')->where('priimek_studenta', 'LIKE', explode(" ", $keyword)[0] . '%')->
            orwhere('ime_studenta', 'LIKE', explode(" ", $keyword)[0] . '%')->where('priimek_studenta', 'LIKE', explode(" ", $keyword)[1] . '%')->get();
        } else {
            $students = Student::where('ime_studenta', 'LIKE', $keyword . '%')->orWhere('priimek_studenta', 'LIKE', $keyword . '%')->orWhere('vpisna_stevilka', $keyword)->get();
        }
        return view('seznamstudentov', ['students' => $students, 'tip' => Auth::user()->type]);
    }
}