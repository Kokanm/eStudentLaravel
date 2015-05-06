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

@endsection