@extends('app')
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <table class="table">
            <tr>
                <th>Ime</th>
                <th>Priimek</th>
                <th>Vpisna stevilka</th>
                <th>E-mail</th>
                <th>Opcije</th>
            </tr>
            @foreach($students as $row)
                <tr>
                    <td>{{ $row->ime_studenta }}</td>
                    <td>{{ $row->priimek_studenta }}</td>
                    <td>{{ $row->vpisna_stevilka }}</td>
                    <td>{{ $row->email_studenta }}</td>
                    @if($tip==2)
                    {!! Form::open(array('action' => array('TiskajController@izpisReferent', $row->vpisna_stevilka))) !!}
                        <td>{!! Form::submit('Možnosti', ['class' => 'btn btn-success btn-xs']) !!}</td>
                    {!! Form::close() !!}
                    @endif
                    @if($tip==3)
                    <td>{!! Form::open(array('action' => array('PodatkiOStudentController@izpisStudent', $row->vpisna_stevilka))) !!}
                        {!! Form::submit('Izpis', ['class' => 'btn btn-success btn-xs']) !!}
                    {!! Form::close() !!}
                    {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneProfesorController@vnesi', $row->vpisna_stevilka))) !!}
                        {!! Form::submit('Vpis ocene', ['class'=>'btn btn-success btn-xs', 'style'=>'margin-top: -40px; margin-left: 50px;']) !!}</td>
                    {!! Form::close() !!}</td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection