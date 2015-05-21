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
    @endif
    <br />


</div>
@endsection