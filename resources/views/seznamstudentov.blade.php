@extends('app')
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <tr>
                <th>Ime</th>
                <th>Priimek</th>
                <th>Vpisna stevilka</th>
                <th>E-mail</th>
                <th>Tiskaj</th>
            </tr>
            @foreach($students as $row)
                <tr>
                    <td>{{ $row->ime_studenta }}</td>
                    <td>{{ $row->priimek_studenta }}</td>
                    <td>{{ $row->vpisna_stevilka }}</td>
                    <td>{{ $row->email_studenta }}</td>
                    {!! Form::open(array('action' => array('TiskajController@izpisReferent', $row->vpisna_stevilka))) !!}
                        <td>{!! Form::submit('Tiskaj', ['class' => 'btn btn-success btn-xs']) !!}</td>
                    {!! Form::close() !!}
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection