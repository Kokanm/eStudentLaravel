@extends('app')
 @section('content')
 <div class="containter">
         <br />
             <div class="row ">
                 <div class="col-md-8 col-md-offset-2" style="font-size: 16px">
                    <table class="table borderless">
                       <tr>
                          <th style="border-top: none;">Šifra predmeta</th>
                          <th style="border-top: none;">Predmet</th>
                          <th style="border-top: none;">Nosilec</th>
                          <th style="border-top: none;">Študijsko leto</th>
                       </tr>
                       <tr>
                         <td style="border-top: none;">{!! $sifra_predmeta !!}</td>
                         <td style="border-top: none;">{!! $ime_predmet !!}</td>
                         <td style="border-top: none;">{!! $profesor !!}</td>
                         <td style="border-top: none;">{!! $stlet !!}</td>
                       </tr>
                    </table>
                   <br />
                 </div>
             </div>
             <hr />
             <br />

     <div class="row">
         <div class="col-md-7 col-md-offset-2">
             <table class="table table-hover">
                 <tr>
                     <th></th>
                     <th>Vpisna številka</th>
                     <th>Priimek in Ime</th>
                     <th>Vrsta vpisa</th>
                 </tr>
                 @for($i=0; $i<count($student); $i++)
                     <tr>
                         <td>{{ $i+1 }}</td>
                         <td>{{ $vpisni[$i] }}</td>
                         <td>{{ $student[$i]->priimek_studenta.", ".$student[$i]->ime_studenta }}</td>
                         <td>{{ $vrsta[$i] }}</td>
                     </tr>
                 @endfor
             </table>
         </div>
     </div>
 <br />

<div class="row">
{!! Form::open( array('url' => 'export' )) !!}
  {!! Form::hidden( 'html' , $html) !!}
  {!! Form::hidden( 'fname' ,  $sifra_predmeta.'-vpisani' ) !!}
          <div class="col-md-offset-9 col-xs-offset-1 col-md-1 col-xs-2">
      {!! Form::submit('Export to PDF', ['name'=>'PDF','class'=>'btn btn-info']) !!}
  </div>
  <div class="col-md-1 col-xs-2" style="padding-left: 33px">
      {!! Form::submit('Export to CSV', ['name'=>'CSV','class'=>'btn btn-info']) !!}
  </div>
{!! Form::close() !!}
</div>

 </div>
 @endsection