@extends('app')
@section('content')
<div class="navbar-form">
    <div class="input-group">
        {!! Form::open(array('url'=>'find')) !!}
            {!! Form::text('keyword', null, ['class'=>'form-control']) !!}
            <div class="input-group-btn">
                {!! Form::submit('search', ['class'=>'btn btn-default']) !!}
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
