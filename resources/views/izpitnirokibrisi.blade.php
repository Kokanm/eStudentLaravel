@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body", style='margin-top: 5px; margin-left: 15px;'>
                    {!! Form::open(array('url' => 'izpitnirokiurejanje')) !!}
                    <div class="form-group">
                        <div class="row">
                            {!! Form::hidden('stleto', $stleto2) !!}
                            {!! Form::hidden('stprogram', $stprogram2) !!}
                            {!! Form::hidden('stletnik', $stletnik2) !!}
                            {!! Form::hidden('id', $izpitni_rok_id) !!}

                            Študentje so že prijavljeni na ta izpitni rok. Želite izpitni rok res odstraniti?<br/><br/>
                            {!! Form::submit('Prekliči', ['name'=>'odstraniIzpitniRokPreklici', 'class'=>'btn btn-primary']) !!}
                            {!! Form::submit('Odstrani', ['name'=>'odstraniIzpitniRokPotrdi', 'class'=>'btn btn-danger', 'style'=>'margin-left: 10px;']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

