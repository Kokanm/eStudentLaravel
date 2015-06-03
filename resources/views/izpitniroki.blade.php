@extends('app')

@section('content')
<div class="container">
    {!! Form::open(array('url' => 'izpitnirokiurejanje')) !!}
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                {!! Form::label('stleto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('stleto', $leto, 0, ['class' => 'form-control', 'id'=>'leto']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::label('stprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('stprogram', $program, 0, ['class' => 'form-control', 'id'=>'program']) !!}
            </div>
            <div class="col-md-2">
                {!! Form::label('stletnik','Letnik', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('stletnik', $letnik, 0, ['class' => 'form-control', 'id'=>'letnik']) !!}
            </div>
            {!! Form::submit('Izpitni roki', ['class'=>'btn btn-primary', 'style'=>'margin-top: 25px; margin-left: 15px;']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection

