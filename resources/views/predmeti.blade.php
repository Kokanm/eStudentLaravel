@extends('app')

@section('content')
<div class="container">
    <h4>PRILOGA 1: PREDMETNIK</h4>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group-sm">
                        <b>Študijski program</b>
                        <p>{!! $studijski_program !!}</p>
                    </div>
                </div>
            </div>
            <br />
            <table class="table">
                <thead>
                    <tr>
                        <th>Učitelj</th>
                        <th>Učna enota</th>
                        <th>Število KT</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=0; $i<count($predmeti); $i++)
                        <tr>
                            <td>{{ $predmeti[$i][0]. ', '. $predmeti[$i][1] }}</td>
                            <td>{{ $predmeti[$i][2] }}</td>
                            <td>{{ $predmeti[$i][3] }}</td>
                        </tr>
                    @endfor
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $sum }}</td>
                        </tr>
                </tbody>
            </table>
            <br />
            <br />
            @if(!empty($prosti))
            {!! Form::open(array('action' => array('IzbirniPredmetiController@izberi', $vpisna))) !!}
                @if(empty($moduli))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('prosti', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('prosti', $prosti , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('prosti2', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('prosti2', $prosti , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('strokovni', 'Strokovno izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('strokovni', $strokovni , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-offset-4 col-md-2">
                            {!! Form::submit('Oddaj') !!}
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('modul', 'Modul 1: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('modul', $moduli , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('modul2', 'Modul 2: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('modul2', $moduli , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('prosti', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('prosti', $prosti , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                {!! Form::label('prosti2', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                                {!! Form::select('prosti2', $prosti , 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-offset-4 col-md-2">
                            {!! Form::submit('Oddaj') !!}
                        </div>
                    </div>
                @endif
            {!! Form::close() !!}

            @else
                <div class="col-md-offset-10 col-md-2">
                    {!! Form::submit('Oddaj', ['class'=>'btn btn-default']) !!}
                </div>
            @endif
            <br />
            <hr />

</div>
@endsection