@extends('app')
 @section('content')
 <div class="containter">
         <br />
             <div class="row ">
                 <div class="col-md-10 col-md-offset-1" style="font-size: 16px">
                    <table class="table borderless">
                       <tr>
                          <th style="border-top: none;">Šifra predmeta</th>
                          <th style="border-top: none;">Predmet</th>
                          <th style="border-top: none;">Nosilec</th>
                          <th style="border-top: none;">Študijsko leto</th>
                          <th style="border-top: none;">Datum</th>
                          <th style="border-top: none;">Ura</th>
                          <th style="border-top: none;">Prostor</th>
                       </tr>
                       <tr>
                         <td style="border-top: none;">{!! $sifra_predmeta !!}</td>
                         <td style="border-top: none;">{!! $ime_predmet !!}</td>
                         <td style="border-top: none;">{!! $profesor !!}</td>
                         <td style="border-top: none;">{!! $stlet !!}</td>
                         <td style="border-top: none;">{{ date("d.m.Y", strtotime($datum)) }}</td>
                         <td style="border-top: none;">{{ date("H:i", strtotime($ura)) }}</td>
                         <td style="border-top: none;">{{ $prostor }}</td>
                       </tr>
                    </table>
                   <br />
                 </div>
             </div>
             <hr />
             <br />

     <div class="row">
         <div class="col-md-10 col-md-offset-1">
             <table class="table">
                 <tr>
                     <th>#</th>
                     <th>Vpisna številka</th>
                     <th>Priimek in Ime</th>
                     <th>Študijsko leto</th>
                     <th colspan="2" style="width: 12%;">Št. polaganj</th>


                 </tr>
                 {{--*/ $i=1 /*--}}
                 @foreach($rez as $row)

                     <tr>
                         <td>{{ $i }}</td>
                         <td>{{ $row->vpisna_stevilka }}</td>

                          <td>{{ $student[$i-1]->priimek_studenta.", ".$student[$i-1]->ime_studenta }}</td>

                         <td>{{ $stLetVpis[$i-1] }}</td>
                         <td>{{ $polaganjeLetos[$i-1]+1 }}</td>
                         <td>{{ $polaganje[$i-1]+1 }}</td>
                     </tr>
                  {{--*/ $i++ /*--}}
                 @endforeach

             </table>
         </div>
     </div>
 <br />

      <div class="row">
      {!! Form::open( array('url' => 'export' )) !!}
          {!! Form::hidden( 'html' , $html) !!}
          {!! Form::hidden( 'fname' ,  $sifra_predmeta.'-prijave-'.date("d-m-Y", strtotime($datum)) ) !!}
          <div class="col-md-offset-9 col-md-1">
              {!! Form::submit('Export to PDF', ['name'=>'PDF','class'=>'btn btn-info']) !!}
          </div>
          <div class="col-md-1" style="padding-left: 33px">
              {!! Form::submit('Export to CSV', ['name'=>'CSV','class'=>'btn btn-info']) !!}
          </div>
      {!! Form::close() !!}
      </div>

 </div>
 @endsection