@extends('app')

@section('content')
<div class="container">
    <h4>VNESI OCENO ZA DOLOČEN IZPITNI ROK</h4><br/>
    {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneController@vnesi', $vp))) !!}
    <div class="form-group">
        <div class="row">
            {!! Form::hidden('stleto', $stleto2) !!}
            {!! Form::hidden('stprogram', $stprogram2) !!}
            {!! Form::hidden('stletnik', $stletnik2) !!}
            {!! Form::hidden('pred', $pred) !!}
            <div class="col-md-3">
                {!! Form::label('izbran_termin','Termin', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('izbran_termin', $termini, 0, ['class' => 'form-control', 'id'=>'izbran_termin']) !!}
            </div>
            <div class="col-md-1">
            {!! Form::label('ocena','Ocena', ['style' => 'font-weight: bold']) !!}
            {!! Form::text('ocena', '', ['class' => 'form-control', 'id'=>'ocena', 'style'=>'width: 40px;']) !!}
            </div>
            {!! Form::submit('Oceni', ['name'=>'termin_oceni', 'class'=>'btn btn-success', 'style'=>'margin-top: 25px; margin-left: 1px;']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    <br/>
    <hr/>
    <br/>

    <h4>VNESI OCENO ZA POLJUBEN DATUM</h4><br/>
    {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneController@vnesi', $vp))) !!}
    <div class="form-group">
        <div class="row">
            {!! Form::hidden('stleto', $stleto2) !!}
            {!! Form::hidden('stprogram', $stprogram2) !!}
            {!! Form::hidden('stletnik', $stletnik2) !!}
            {!! Form::hidden('pred', $pred) !!}
            <div class="col-md-3">
                {!! Form::label('datepicker2','Datum', ['style' => 'font-weight: bold']) !!}
                {!! Form::text('datepicker2', '', ['id' => 'datepicker2', 'class' => 'form-control']) !!}
            </div>
            <div class="col-md-1">
                {!! Form::label('ocena','Ocena', ['style' => 'font-weight: bold']) !!}
                {!! Form::text('ocena', '', ['class' => 'form-control', 'id'=>'ocena', 'style'=>'width: 40px;']) !!}
            </div>
            {!! Form::submit('Oceni', ['name'=>'termin_nov_oceni', 'class'=>'btn btn-success', 'style'=>'margin-top: 25px; margin-left: 1px;']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection