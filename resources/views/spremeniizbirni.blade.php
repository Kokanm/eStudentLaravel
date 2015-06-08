@extends('app')

@section('content')
    @if(!empty($prosti))
        @if($tips==0)
            {!! Form::open(array('action' => array('IzbirniPredmetiController@izberi', $vpisna))) !!}
        @else
            {!! Form::open(array('action' => array('IzbirniPredmetiController@izberi2', $vpisna))) !!}
        @endif
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
        @elseif(!empty($modularni))
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni1', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni1', $modularni , 0, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni2', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni2', $modularni , 0, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni3', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni3', $modularni , 0, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni4', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni4', $modularni , 0, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni5', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni5', $modularni , 0, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni6', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni6', $modularni , 0, ['class' => 'form-control']) !!}
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
            <br />
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
            <a href="{{ url('/') }}">{!! Form::button('Oddaj', ['class'=>'btn btn-default']) !!}</a>
        </div>
    @endif
    <br />
    <hr />
@endsection