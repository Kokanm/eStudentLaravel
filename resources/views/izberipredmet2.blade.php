@extends('app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        {!! Form::open(array('action' => array('SeznamStudentovPredmetaController@studentiPredmeta', $row->vpisna_stevilka))) !!}
            <div class="form-group">
                <div class="col-md-3">
                    {!! Form::label('stleto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                    {!! Form::select('stleto', $leto, 0, ['class' => 'form-control']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('stletnik','Letnik', ['style' => 'font-weight: bold']) !!}
                    {!! Form::select('stletnik', $letnik, 0, ['class' => 'form-control', 'id'=>'letnik']) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::label('stprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                    {!! Form::select('stprogram', $program, 0, ['class' => 'form-control', 'id'=>'program']) !!}
                </div>

                <div class="col-md-1">
                    {!! Form::submit('Naprej', ['class'=>'btn btn-success', 'style' => 'margin-top: 24px']) !!}
                </div>
            </div>
        </div>
    </div>
    <br />
    <hr />
    <br />
    <table class="table">
        <thead>
            <tr>
                <th>Predmet</th>
                <th>Seznam prijavljenih</th>
            </tr>
        </thead>
        <tbody>
        @for($i=0; $i<count($predmet); $i++)
            <tr>
                <td>{{ $predmet[$i] }}</td>
                <td>{!! Form::submit('Vrni seznam', ['class' => 'btn btn-success btn-xs', 'name'=>'vrni'.$i]) !!}</td>
            </tr>
        @endfor
        </tbody>
    </table>
    {!! Form::close() !!}
</div>
@endsection