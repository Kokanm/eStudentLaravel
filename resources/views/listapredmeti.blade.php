
 @extends('app')
 @section('content')
 <div class="containter">
     <div class="row">
         <div class="col-md-10 col-md-offset-1">
             <table class="table">
                 <tr>
                     <th>Ime predmet</th>
                     <th>Sifra predmeta</th>
                     <th>Opcije</th>
                 </tr>
                 @foreach($predmeti as $row)
                     <tr>
                         <td>{{ $row->naziv_predmeta }}</td>
                         <td>{{ $row->sifra_predmeta }}</td>
                         {!! Form::open(array('action' => array( $url.'@izberi3', $row->sifra_predmeta))) !!}
                            {!! Form::hidden ('id_leto', $id_leto)!!}
                             <td>{!! Form::submit('Izberi', ['class' => 'btn btn-success btn-xs']) !!}</td>
                         {!! Form::close() !!}

                     </tr>
                 @endforeach
             </table>
         </div>
     </div>
 </div>
 @endsection