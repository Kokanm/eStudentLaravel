@extends('app')
@section('content')
<div class="row">
    <div class="col-md-offset-2 col-md-1">
        {!! Form::open(array('action' => array('IzpisVpisnegaListaController@pregled', $vp))) !!}
            {!! Form::submit('Vpisni list', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
    <div class="col-md-1">
        {!! Form::open(array('action' => array('PotrditevVpisaController@natisni', $vp))) !!}
            {!! Form::submit('Potrdilo o vpisu', ['class'=>'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection