@extends('app')
@section('content')
<div class="container">
    @if(!empty($predmeti))
        {!! Form::open(array('action' => array('NajdiIzvajalcaController@dodaj', $pomos))) !!}
        <div class="row">

                <div class="form-group">
                    <div class="col-md-3">
                        {!! Form::label('stleto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('stleto', $leto, $stleto, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('stletnik','Letnik', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('stletnik', $letnik, $stletnik, ['class' => 'form-control', 'id'=>'letnik']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('stprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('stprogram', $program, $stprogram, ['class' => 'form-control', 'id'=>'program']) !!}
                    </div>
                </div>
        </div>
        <hr/>
        <br/>
        <br/>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Predmet</th>
                        <th>Izvajalec 1</th>
                        <th>Izvajalec 2</th>
                        <th>Izvajalec 3</th>
                    </tr>
                </thead>
                <tbody>
                    {{--*/ $i = 0 /*--}}
                    @foreach($izvPredmeti as $row)
                    <tr>
                        <td class="col-md-offset-2 col-md-1 col-xs-3">{{ $predmeti[$row->sifra_predmeta] }}</td>
                        <td class="col-md-1 col-xs-3">{!! Form::select('prof1'.$i, $profesor, $row->sifra_profesorja, ['class' => 'form-control']) !!}</td>
                        <td class="col-md-1 col-xs-3">{!! Form::select('prof2'.$i, $profesor, $row->sifra_profesorja2, ['class' => 'form-control']) !!}</td>
                        <td class="col-md-1 col-xs-3">{!! Form::select('prof3'.$i, $profesor, $row->sifra_profesorja3, ['class' => 'form-control']) !!}</td>
                    </tr>
                    {{--*/ $i+=1 /*--}}
                    @endforeach
                </tbody>
            </table>
            <div class="col-md-offset-10 col-md-1">
                {!! Form::submit('Posodobi', ['class'=>'btn btn-success', 'style' => 'margin-bottom: 50px; margin-left:84px']) !!}
            </div>
        {!! Form::close() !!}
    @else
        <div class="row">
            {!! Form::open(array('url'=>'najdiprofesor')) !!}
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
            {!! Form::close() !!}
        </div>
        <hr/>
        <br/>
        <br/>
@endif
    <br />
</div>
@endsection
