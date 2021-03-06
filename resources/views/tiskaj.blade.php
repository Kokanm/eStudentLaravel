@extends('app')
@section('content')
<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('IzpisVpisnegaListaController@pregled', $vp))) !!}
            {!! Form::submit('Vpisni list', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>

<hr/>

<div class="row">
    <div class = "navbar-form">
        <div class="col-md-offset-2 col-md-3 input-group">
            {!! Form::open(array('action' => array('PotrditevVpisaController@natisni', $vp))) !!}
                {!! Form::text('stevilo', null, ['class'=>'form-control']) !!}
                <div class="input-group-btn">
                    {!! Form::submit('Potrdilo o vpisu', ['class'=>'btn btn-default']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('ReferentController@dodajZeton', $vp))) !!}
            {!! Form::submit('Žeton', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('PrijavaNaIzpitController@RokiR', $vp))) !!}
            {!! Form::submit('Prijava na izpit', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneController@vnesi', $vp))) !!}
            {!! Form::submit('Vpis ocene', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('KartotecniListReferentController@vrniVsa', $vp))) !!}
            {!! Form::submit('Kartotečni list', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('SpremeniIzbirniPredmetiController@izbirni', $vp))) !!}
            {!! Form::submit('Izbirni predmeti', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection