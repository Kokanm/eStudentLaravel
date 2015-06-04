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
                     @if($trig==1)
                        <th>Priimek in Ime</th>
                     @endif
                     <th>Studijsko leto</th>
                     <th colspan="2" style="width: 12%;">Št. polaganj</th>
                     <th>Tocke</th>
                     <th>Ocena</th>

                 </tr>
                 {{--*/ $i=1 /*--}}
                 @foreach($rez as $row)

                     <tr>
                         <td>{{ $i }}</td>
                         <td>{{ $row->vpisna_stevilka }}</td>
                          @if($trig==1)
                          <td>{{ $student[$i-1]->priimek_studenta.", ".$student[$i-1]->ime_studenta }}</td>
                          @endif
                            <td>{{ $stLetVpis[$i-1] }}</td>
                            <td>{{ $polaganjeLetos[$i-1]+1 }}</td>
                            <td>{{ $polaganje[$i-1]+1 }}</td>

                         <td>{{ $row->tocke_izpita }}</td>
                         <td>{{ $row->ocena }}</td>
                     </tr>
                  {{--*/ $i++ /*--}}
                 @endforeach

             </table>
         </div>
     </div>
 </div>
 @endsection