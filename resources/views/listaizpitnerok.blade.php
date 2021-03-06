@extends('app')
 @section('content')
 <div class="containter">
     <div class="row">
         <div class="col-md-10 col-md-offset-1">
             <table class="table">
                 <tr>
                     <th>Ime predmet</th>
                     <th>Sifra predmeta</th>
                     <th>Letnik</th>
                     <th>Professor</th>
                     <th>Datum</th>
                     <th>Opcije</th>
                 </tr>
                 @foreach($rok as $row)
                     <tr>
                         <td>{{ $row[0] }}</td>
                         <td>{{ $row[1] }}</td>
                         <td>{{ $row[2] }}</td>
                         <td>{{ $row[3] }}</td>
                         <td>{{ date("d.m.Y", strtotime($row[4])) }}</td>
                         {!! Form::open(array('action' => array($url.'@izpisi', $row[1], $row[4] ))) !!}
                             {!! Form::hidden ( 'row', $row[5]->sifra_profesorja . "-" . $row[5]->predavalnica . "-" . $row[5]->ura . "-" . $row[5]->sifra_studijskega_leta)!!}
                             <td>
                             @if($url=="IzpisiRezultatiController" || $url=="OceneIzpitController")
                             {!! Form::submit('Izpis brez ime', [ 'name' => 'brez' , 'class' => 'btn btn-success btn-xs']) !!}
                             {!! Form::submit('Izpis z ime', ['name' => 'ime','class' => 'btn btn-success btn-xs', ]) !!}
                             @endif
                             @if($url=="PrijavljeniIzpitController")
                             {!! Form::submit('Izpisi prijavljeni', ['class' => 'btn btn-success btn-xs']) !!}
                             @endif
                             </td>
                         {!! Form::close() !!}

                     </tr>
                 @endforeach
             </table>
         </div>
     </div>
 </div>
 @endsection