@extends('app')

@section('content')
<div class="container">
    <div class="col-md-6 col-md-offset-2 potrdilo">
        <h2>Potrdilo o vpisu</h2>
        <h4>vpisna številka: {!! $vse['vpisnastevilka'] !!}</h4>
        <br />
        <p>Potrjujemo, da je {!! $vse['priimekime'] !!}</p>
        <p>rojen-a {!! $vse['datum'] !!}, v kraju {!! $vse['kraj'] !!}</p>
        <p>vpisan-a v {!! $vse['letnik'] !!} letniku v   {!! Form::checkbox('zimski', 1, true) !!}zimskem/{!! Form::checkbox('letni', 1, true) !!}letnem semestru</p>
        <p>v študijskem letu {!! date('Y')."/".(date('Y')+1) !!}</p>
        <p>kot {!! $vse['nacin'] !!} študent-ka</p>
        <p>program, smer, skupina {!! $vse['program'] !!}</p>
        <br />
        <p>Ljubljana, dne {!! date('d.m.Y') !!}</p>
    </div>
    <div class="motrdilo col-md-3">
        Uradne opmobe:
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        Žig:
        <br />
        <br />
        <p>Dekan:</p>
    </div>
    <br />
    <br />
    <div class="row col-md-offset-10 col-md-2">
        {!! Form::button('Tiskaj', ['class' => 'btn btn-info buttonClass', 'onClick' => 'window.print()', 'style'=>'float:left']) !!}
    </div>

    <div class="montainer">
        <div class="col-md-6 col-md-offset-2 potrdilo">
            <h2>Potrdilo o vpisu</h2>
            <h4>vpisna številka: {!! $vse['vpisnastevilka'] !!}</h4>
            <br />
            <p>Potrjujemo, da je {!! $vse['priimekime'] !!}</p>
            <p>rojen-a {!! $vse['datum'] !!}, v kraju {!! $vse['kraj'] !!}</p>
            <p>vpisan-a v {!! $vse['letnik'] !!} letniku v   {!! Form::checkbox('zimski', 1, true) !!}zimskem/{!! Form::checkbox('letni', 1, true) !!}letnem semestru</p>
            <p>v študijskem letu {!! date('Y')."/".(date('Y')+1) !!}</p>
            <p>kot {!! $vse['nacin'] !!} študent-ka</p>
            <p>program, smer, skupina {!! $vse['program'] !!}</p>
            <br />
            <p>Ljubljana, dne {!! date('d.m.Y') !!}</p>
        </div>
        <div class="motrdilo col-md-3">
            Uradne opmobe:
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            Žig:
            <br />
            <br />
            <p>Dekan:</p>
        </div>
        <br />
        <br />
    </div>
    <div class="montainer">
        <div class="col-md-6 col-md-offset-2 potrdilo">
            <h2>Potrdilo o vpisu</h2>
            <h4>vpisna številka: {!! $vse['vpisnastevilka'] !!}</h4>
            <br />
            <p>Potrjujemo, da je {!! $vse['priimekime'] !!}</p>
            <p>rojen-a {!! $vse['datum'] !!}, v kraju {!! $vse['kraj'] !!}</p>
            <p>vpisan-a v {!! $vse['letnik'] !!} letniku v   {!! Form::checkbox('zimski', 1, true) !!}zimskem/{!! Form::checkbox('letni', 1, true) !!}letnem semestru</p>
            <p>v študijskem letu {!! date('Y')."/".(date('Y')+1) !!}</p>
            <p>kot {!! $vse['nacin'] !!} študent-ka</p>
            <p>program, smer, skupina {!! $vse['program'] !!}</p>
            <br />
            <p>Ljubljana, dne {!! date('d.m.Y') !!}</p>
        </div>
        <div class="motrdilo col-md-3">
            Uradne opmobe:
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            Žig:
            <br />
            <br />
            <p>Dekan:</p>
        </div>
        <br />
        <br />
    </div>
    <div class="montainer">
        <div class="col-md-6 col-md-offset-2 potrdilo">
            <h2>Potrdilo o vpisu</h2>
            <h4>vpisna številka: {!! $vse['vpisnastevilka'] !!}</h4>
            <br />
            <p>Potrjujemo, da je {!! $vse['priimekime'] !!}</p>
            <p>rojen-a {!! $vse['datum'] !!}, v kraju {!! $vse['kraj'] !!}</p>
            <p>vpisan-a v {!! $vse['letnik'] !!} letniku v   {!! Form::checkbox('zimski', 1, true) !!}zimskem/{!! Form::checkbox('letni', 1, true) !!}letnem semestru</p>
            <p>v študijskem letu {!! date('Y')."/".(date('Y')+1) !!}</p>
            <p>kot {!! $vse['nacin'] !!} študent-ka</p>
            <p>program, smer, skupina {!! $vse['program'] !!}</p>
            <br />
            <p>Ljubljana, dne {!! date('d.m.Y') !!}</p>
        </div>
        <div class="motrdilo col-md-3">
            Uradne opmobe:
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            Žig:
            <br />
            <br />
            <p>Dekan:</p>
        </div>
        <br />
        <br />
    </div>
    <div class="montainer">
        <div class="col-md-6 col-md-offset-2 potrdilo">
            <h2>Potrdilo o vpisu</h2>
            <h4>vpisna številka: {!! $vse['vpisnastevilka'] !!}</h4>
            <br />
            <p>Potrjujemo, da je {!! $vse['priimekime'] !!}</p>
            <p>rojen-a {!! $vse['datum'] !!}, v kraju {!! $vse['kraj'] !!}</p>
            <p>vpisan-a v {!! $vse['letnik'] !!} letniku v   {!! Form::checkbox('zimski', 1, true) !!}zimskem/{!! Form::checkbox('letni', 1, true) !!}letnem semestru</p>
            <p>v študijskem letu {!! date('Y')."/".(date('Y')+1) !!}</p>
            <p>kot {!! $vse['nacin'] !!} študent-ka</p>
            <p>program, smer, skupina {!! $vse['program'] !!}</p>
            <br />
            <p>Ljubljana, dne {!! date('d.m.Y') !!}</p>
        </div>
        <div class="motrdilo col-md-3">
            Uradne opmobe:
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            Žig:
            <br />
            <br />
            <p>Dekan:</p>
        </div>
        <br />
        <br />
    </div>
    <div class="montainer">
        <div class="col-md-6 col-md-offset-2 potrdilo">
            <h2>Potrdilo o vpisu</h2>
            <h4>vpisna številka: {!! $vse['vpisnastevilka'] !!}</h4>
            <br />
            <p>Potrjujemo, da je {!! $vse['priimekime'] !!}</p>
            <p>rojen-a {!! $vse['datum'] !!}, v kraju {!! $vse['kraj'] !!}</p>
            <p>vpisan-a v {!! $vse['letnik'] !!} letniku v   {!! Form::checkbox('zimski', 1, true) !!}zimskem/{!! Form::checkbox('letni', 1, true) !!}letnem semestru</p>
            <p>v študijskem letu {!! date('Y')."/".(date('Y')+1) !!}</p>
            <p>kot {!! $vse['nacin'] !!} študent-ka</p>
            <p>program, smer, skupina {!! $vse['program'] !!}</p>
            <br />
            <p>Ljubljana, dne {!! date('d.m.Y') !!}</p>
        </div>
        <div class="motrdilo col-md-3">
            Uradne opmobe:
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            Žig:
            <br />
            <br />
            <p>Dekan:</p>
        </div>
        <br />
        <br />
    </div>

</div>
@endsection