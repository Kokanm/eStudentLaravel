@extends('app')
@section('content')
<div class="container">
    @if($errors->any())
        <ul class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    @if(!empty($prosti))
        {!! Form::open(array('action' => array('SpremeniIzbirniPredmetiController@izberi', $vp))) !!}
        <h4>{{ $ime." (".$vp.")"  }}</h4>
        @if(!empty($strokovni))
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('prosti', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('prosti', $prosti , $pr1, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('prosti2', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('prosti2', $prosti , $pr2, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('strokovni', 'Strokovno izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('strokovni', $strokovni , $str, ['class' => 'form-control']) !!}
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
                        {!! Form::label('modularni1', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni1', $modularni , $mod1, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni2', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni2', $modularni , $mod2, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni3', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni3', $modularni , $mod3, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni4', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni4', $modularni , $mod4, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni5', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni5', $modularni , $mod5, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('modularni6', 'Modularni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('modularni6', $modularni , $mod6, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('prosti', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('prosti', $prosti , $pr1, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-inline">
                        {!! Form::label('prosti2', 'Prosto izbirni predmet: ', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('prosti2', $prosti , $pr2, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-offset-4 col-md-2">
                    {!! Form::submit('Oddaj') !!}
                </div>
            </div>
            <br />
        @endif
        {!! Form::close() !!}

    @else
        <div class="col-md-offset-10 col-md-2">
            <a href="{{ url('/') }}">{!! Form::button('Spremeni', ['class'=>'btn btn-default']) !!}</a>
        </div>
    @endif
    <br />
    <hr />
</div>
@endsection