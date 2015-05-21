@extends('app')
@section('content')
@if (Session::has('message'))
    <b style="color:red; font-size: large;">{{ Session::get('message') }}</b>
@endif
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <tr>
                <th>Letnik</th>
                <th>Predmet</th>
                <th>Izvajalec</th>
                <th>Datum</th>
                <th>Čas</th>
                <th>Opombe</th>
                <th></th>
            </tr>
            @for($i=0; $i<count($rok); $i++)
                @if(!empty($rok[$i][0]))
                <tr>
                    <td>{{ $rok[$i][0]->sifra_letnika.". letnik" }}</td>
                    <td>{{ $predmeti[$i] }}</td>
                    <td>{{ $profesorji[$i] }}</td>
                    <td>{{ date("d.m.Y", strtotime($rok[$i][0]->datum)) }}</td>
                    <td>{{ date("H:i", strtotime($rok[$i][0]->ura))."  ".$rok[$i][0]->predavalnica }}</td>
                    <td>{{ $rok[$i][0]->opombe." ".$msg[$i] }}</td>
                    @if($rok[$i][1]==0)
                        {!! Form::open(array('action' => array('PrijavaNaIzpitController@Prijava', $vpisna." ".$rok[$i][0]->sifra_letnika." ".$rok[$i][0]->sifra_predmeta." ".
                                $rok[$i][0]->sifra_profesorja." ".$rok[$i][0]->sifra_studijskega_programa." ".$rok[$i][0]->sifra_studijskega_leta." ".$rok[$i][0]->datum." ".$tip[$i]))) !!}
                            @if($msg[$i] != "")
                                <td>{!! Form::submit('Prijavi se', ['class' => 'btn btn-success btn-xs', 'disabled']) !!}</td>
                            @else
                                <td>{!! Form::submit('Prijavi se', ['class' => 'btn btn-success btn-xs']) !!}</td>
                            @endif
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(array('action' => array('PrijavaNaIzpitController@Odjava', $vpisna." ".$rok[$i][0]->sifra_letnika." ".$rok[$i][0]->sifra_predmeta." ".
                                $rok[$i][0]->sifra_profesorja." ".$rok[$i][0]->sifra_studijskega_programa." ".$rok[$i][0]->sifra_studijskega_leta." ".$rok[$i][0]->datum." ".$tip[$i]))) !!}
                            <td>{!! Form::submit('Odjavi se', ['class' => 'btn btn-success btn-xs']) !!}</td>
                        {!! Form::close() !!}
                    @endif
                </tr>
                @endif
            @endfor
        </table>
    </div>
</div>
@endsection