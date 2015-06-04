@extends('app')
 @section('content')
 <div class="containter">
           <div class="col-md-offset-1">
           <div class="row ">
             <div class="col-md-2 col-xs-4 ">
                 <div class="form-group-sm">
                     <p>Ime predmeta</p>
                     <b>{!! $ime_predmet !!}</b>
                 </div>
             </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    <p>Sifra predmeta</p>
                    <b>{!! $sifra_predmeta !!}</b>
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    <p>Nosilec</p>
                    <b>{!! $profesor !!}</b>
                </div>
           </div>
           <div class="col-md-2 col-xs-4">
                           <div class="form-group-sm">
                               <p>Studijsko leto</p>
                               <b>{!! $stlet !!}</b>
                           </div>
                      </div>
           </div>
          <br />
          <div class="row">
              <div class="col-md-2 col-xs-4">
                  <div class="form-group-sm">
                      <p>Datum</p>
                      <b>{{ date("d.m.Y", strtotime($datum)) }}</b>
                  </div>
              </div>
              <div class="col-md-2 col-xs-4">
                    <div class="form-group-sm">
                        <p>Ura</p>
                        <b>{{ date("H:i", strtotime($ura)) }}</b>
                    </div>
                </div>
              <div class="col-md-2 col-xs-4">
                  <div class="form-group-sm">
                      <p>Prostor</p>
                      <b>{{ $prostor }}</b>
                  </div>
              </div>
          </div>
          <br />
          </div>

     <div class="row">
         <div class="col-md-10 col-md-offset-1">
             <table class="table">
                 <tr>
                     <th>#</th>
                     <th>Vpisna stevilka</th>
                     <th>Priimek in Ime</th>
                     <th>Studijsko leto</th>
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