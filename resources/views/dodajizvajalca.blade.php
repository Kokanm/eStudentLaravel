@extends('app')
@section('content')
<div class="row">
    {!! Form::open(array('url'=>'dodajprofesor')) !!}
        <div class="form-group">
            <div class="col-md-offset-1 col-md-3">
                {!! Form::label('leto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('leto', $leto, 0, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-2">
                {!! Form::label('letnik','Letnik', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('letnik', $letnik, 0, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-3">
                {!! Form::label('program','Študijski program', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('program', $program, 0, ['class' => 'form-control']) !!}
            </div>

            <div class="col-md-1">
                {!! Form::submit('dodaj', ['class'=>'btn btn-success', 'style' => 'margin-top: 24px']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>
@endsection
