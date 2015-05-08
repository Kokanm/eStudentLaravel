@extends('app')
@section('content')
<div class="row">
    @if($pom == 0)
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
    @else
        <div class="col-md-10 col-md-offset-1">
            <h3>Kandidate:</h3>
            <br />
            <table class="table">
                <tr>
                    <th>Ime</th>
                    <th>Priimek</th>
                    <th>E-mail</th>
                    <th>Vpisi</th>
                </tr>
                @foreach($kandidati as $row)
                    <tr>
                        <td>{{ $row->ime_kandidata }}</td>
                        <td>{{ $row->priimek_kandidata }}</td>
                        <td>{{ $row->email_kandidata }}</td>
                        {!! Form::open(array('action' => array('VpisniListReferentController@select', str_replace(".", "aaaAAAbbbBBBdddDDDcccCCCPOPTart", $row->email_kandidata)))) !!}
                            <td>{!! Form::submit('Vpisi', ['class' => 'btn btn-success btn-xs']) !!}</td>
                        {!! Form::close() !!}
                    </tr>
                @endforeach
            </table>
            <hr/>
            <br />
            <br />
            <br />

            @if(!empty($studenti))
                <h3>Nevpisani študenti:</h3>
                <br />
                <table class="table">
                    <tr>
                        <th>Ime</th>
                        <th>Priimek</th>
                        <th>Vpisna stevilka</th>
                        <th>Vpisi</th>
                    </tr>
                    @for($i=0; $i<count($studenti); $i++)
                        @foreach($studenti[$i] as $row)
                            <tr>
                                <td>{{ $row->ime_studenta }}</td>
                                <td>{{ $row->priimek_studenta }}</td>
                                <td>{{ $row->vpisna_stevilka }}</td>
                            {!! Form::open(array('action' => array('VpisniListReferentController@select', $row->vpisna_stevilka))) !!}
                                 <td>{!! Form::submit('Vpisi', ['class' => 'btn btn-success btn-xs']) !!}</td>
                            {!! Form::close() !!}
                            </tr>
                        @endforeach
                    @endfor
                </table>
                <br />
                <br />
                <br />
            @endif
        </div>
    @endif
</div>
@endsection