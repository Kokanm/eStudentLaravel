@extends('app')

@section('content')
<div class="container">
     @if($tip==2)
    {!! Form::open(array('url'=> $url)) !!}
    @else
    {!! Form::open(array('url'=> $url.'p')) !!}
    @endif
        {!! Form::label('st_let','Leto izvajanja') !!}
        {!! Form::select('st_let', $let, null, ['class' => 'form-control']) !!}

        @if($tip==2)
        {!! Form::label('keyword','Ime predmeta') !!}
        {!! Form::text('keyword', null, ['class'=>'form-control']) !!}
        @endif
        <div class="input-group-btn">
                {!! Form::submit('Next', ['class'=>'btn btn-default']) !!}
        </div>
    {!! Form::close() !!}

</div>
@endsection