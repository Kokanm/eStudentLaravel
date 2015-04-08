@extends('app')
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <table class="table">
            <tr>
                <th>Ime</th>
                <th>Priimek</th>
                <th>Vpisna stevilka</th>
                <th>Potrdi vpisa</th>
            </tr>
            @for($i=0; $i<count($studenti); $i++)
                @foreach($studenti[$i] as $row)
                    <tr>
                        <td>{{ $row->ime_studenta }}</td>
                        <td>{{ $row->priimek_studenta }}</td>
                        <td>{{ $row->vpisna_stevilka }}</td>
                        {!! Form::open(array('action' => array('IzpisVpisnegaListaController@vpisnilist', $row->vpisna_stevilka))) !!}
                            <td>{!! Form::submit('Potrdi', ['class' => 'btn btn-success btn-xs']) !!}</td>
                        {!! Form::close() !!}
                    </tr>
                @endforeach
            @endfor
        </table>
    </div>
</div>
@endsection