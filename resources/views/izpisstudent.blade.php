@extends('app')

@section('content')
<div class="container">

 <h2>Podatki za študenta {{$ime}} {{$priimek}}  </h2>
    <div class="row">
       <div class="col-md-2 col-xs-4">
           <div class="form-group-sm">
               <p>Vpisna številka</p>
               <b>{!! $vpisna !!}</b>
           </div>
       </div>
    </div>
    <br />
    <div class="row">
      <div class="col-md-2 col-xs-4">
          <div class="form-group-sm">
              <p>Ime</p>
              <b>{!! $ime !!}</b>
          </div>
      </div>
      <div class="col-md-2 col-xs-4">
          <div class="form-group-sm">
              <p>Priimek</p>
              <b>{!! $priimek !!}</b>
          </div>
      </div>
      <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Datum </p>
                <b>{!! $rojstni !!}</b>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Telefonska Stevilka</p>
                <b>{!! $tel !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>E-mail</p>
                <b>{!! $email !!}</b>
            </div>
        </div>
    </div>
    <br />
    <br />

    @if(count($ocene)==0)
           <h4>Nima visani predmetov pri vas</h4>
    @else
    <h3>Ocene</h3>
        <hr />
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
             <table class="table">
                 <tr>
                     <th>#</th>
                     <th>Šifra</th>
                     <th>Predmet</th>
                     <th>Študijsko leto</th>
                     <th>Letnik</th>
                     <th>Datum</th>
                     <th>Tocke izpit</th>
                     <th>Ocena</th>

                 </tr>
                 @foreach($ocene as $row)
                     <tr>
                         <td>{{ $row[0] }}</td>
                         <td>{{ $row[1] }}</td>
                         <td>{{ $row[2] }}</td>
                         <td>{{ $row[3] }}</td>
                         <td>{{ $row[4] }}</td>
                         @if($row[5]!="")
                         <td>{{ date('d.m.Y',strtotime($row[5])) }}</td>
                         @else
                         <td>{{ $row[5] }}</td>
                         @endif
                         <td>{{ $row[6] }}</td>
                         <td>{{ $row[7] }}</td>
                      </tr>
                 @endforeach
             </table>
        </div>
    </div>
    @endif

    <br />
    <br />
    <h3>Sklepov</h3>
    <hr />
    @if(count($sklep)==0)
       <h4>Nima sklepov za ta študent</h4>
    @else
        <div class="row">
           <div class="col-md-10 col-md-offset-1">
               <table class="table">
                   <tr>
                       <th>Besedilo</th>
                       <th>Izdajatelj</th>
                       <th>Datum</th>
                       <th>Veljaven do</th>
                   </tr>
                   @foreach($sklep as $row)
                       <tr>
                           <td>{{ $row->besedilo }}</td>
                           <td>{{ $row->izdajatelj }}</td>
                           <td>{{ date('d.m.Y',strtotime($row->datum)) }}</td>
                           <td>{{ date('d.m.Y',strtotime($row->veljaven_do)) }}</td>
                        </tr>
                   @endforeach
               </table>
            </div>
        </div>
    @endif
    <br />
    <div class="row">
        <div class="col-md-2 col-xs-4">
        {!! Form::open(array('action' => array( 'KartotecniListReferentController@vrniVsa', $vpisna ))) !!}
        {!! Form::submit('Izpis Kartotecni list', ['name'=>'PDF','class'=>'btn btn-info']) !!}
        {!! Form::close() !!}
        </div>
        <div class="col-md-2 col-xs-4">
        {!! Form::open(array('action' => array( 'IndividualniVnosKoncneOceneProfesorController@vnesi', $vpisna ))) !!}
        {!! Form::submit('Vpis ocene', ['name'=>'PDF','class'=>'btn btn-info']) !!}
        {!! Form::close() !!}
        </div>
    </div>

    </div>
</div>
<br />
<br />
<br />
@endsection