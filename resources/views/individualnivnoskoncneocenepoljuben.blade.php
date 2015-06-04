@extends('app')

@section('content')
<div class="container">
    {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneController@vnesi', $vp))) !!}
    <div class="form-group">
        <div class="row">
            <div class="col-md-7">
                {!! Form::hidden('stleto', $stleto2) !!}
                {!! Form::hidden('stprogram', $stprogram2) !!}
                {!! Form::hidden('stletnik', $stletnik2) !!}
                {!! Form::label('pred','Predmet', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('pred', $predmeti, 0, ['class' => 'form-control', 'id'=>'predmeti']) !!}
            </div>
            {!! Form::submit('Nadaljuj', ['name'=>'izberi_predmet', 'class'=>'btn btn-primary', 'style'=>'margin-top: 25px; margin-left: 15px;']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection