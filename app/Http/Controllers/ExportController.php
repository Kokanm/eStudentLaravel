<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use PDF;

include "simple_html_dom.php";

class ExportController extends Controller {


    function input(){
        $in=Input::get('html');
        $fname=Input::get('fname');

        if( Input::get('PDF') ){
            $html=view( 'pdfcreator', ['data'=>$in] )->renderSections()['content'];
            //echo($html);
            $pdf=PDF::loadHTML($html);
            return $pdf->stream($fname.'.pdf');
        }
        if( Input::get('CSV') ){
            //echo "EXPORTING CSV";
            $this->exportCSV($in,$fname);
        }


    }

    function exportCSV($in,$fname){
        $html = str_get_html($in);

        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename='.$fname.'.csv');

        $fp = fopen("php://output", "w");

        foreach($html->find('tr') as $element)
        {
            $td = array();
            foreach( $element->find('th') as $row)
            {
                $td [] = $row->plaintext;
            }
            fputcsv($fp, $td);

            $td = array();
            foreach( $element->find('td') as $row)
            {
                $td [] = $row->plaintext;
            }
            fputcsv($fp, $td);
        }


        fclose($fp);
    }




}
