@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <h2 style="padding-left: 14px">{{ $name }}</h2>
    </div>
    <div class="row">
        <div class="col-md-1">
            {!! Form::submit('Vsa polaganja', ['class'=>'btn btn-info '.$active[0]]) !!}
        </div>
        <div class="col-md-1" style="padding-left: 33px">
            {!! Form::submit('Zadnja polaganja', ['class'=>'btn btn-info '.$active[1]]) !!}
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-4">
           {!! Form::select('studiskiprogram', $studijski_programi, 0, ['class' => 'form-control']) !!}
        </div>
    </div>
    <hr />
    <br />
    @for($i=1; $i<count($studijski_programi); $i++)
        <div class="row">
            <h3>{{ $i.". ".substr($studijski_programi[$i], 7) }}</h3>
        </div>
        @for($j=1; $j<4; $j++)
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Študijsko leto</th>
                            <th>Letnik</th>
                            <th>Vrste vpisa</th>
                            <th>Način študija</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ "letooooooo" }}</td>
                            <td>{{ $j.". letnik" }}</td>
                            <td>{{ "redni" }}</td>
                            <td>{{ "izredni" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Šifra</th>
                            <th>Predmet</th>
                            <th>Ocenil</th>
                            <th>Letnik</th>
                            <th>Datum</th>
                            <th>#polaganje leto</th>
                            <th>#polaganje skupaj</th>
                            <th>KT</th>
                            <th>Ocena</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($k=1; $k<6; $k++)
                            <tr>
                                <td>{{ $k }}</td>
                                <td>{{ "6310".$k }}</td>
                                <td>{{ "predmet ".$k }}</td>
                                <td>{{ "profesor ".$k }}</td>
                                <td>{{ "letnik ".$k }}</td>
                                <td>{{ "datum ".$k }}</td>
                                <td>{{ "#".$k }}</td>
                                <td>{{ "#".($k+2) }}</td>
                                <td>{{ "6" }}</td>
                                <td>{{ "10" }}</td>
                            </tr>
                        @endfor
                        <tr>
                            <td colspan="2" style="font-weight: bold; font-size: medium; padding-left: 10px">Število opravljenih izpitov: </td>
                            <td colspan="2">10</td>
                            <td colspan="3" style="font-weight: bold; font-size: medium; padding-left: 10px">Vsota KT in povprečno oceno:</td>
                            <td>{{ "60" }}</td>
                            <td>{{ "8.9" }}</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <hr />
                <br />
            </div>
        @endfor
    @endfor
    <br />
    <hr />
    <br />
    <div class="row">
        <h3>Skupno povprečno oceno in število kreditnih točk</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Študijski program</th>
                    <th>Število opravljenih izpitov</th>
                    <th>Kreditne točke</th>
                    <th>Skupno povprečje</th>
                </tr>
            </thead>
            <tbody>
                @for($i=1; $i<2; $i++)
                    <tr>
                        <td>{{ "Študijski program ".$i }}</td>
                        <td>{{ "10" }}</td>
                        <td>{{ "60" }}</td>
                        <td>{{ "10" }}</td>
                    </tr>
                @endfor
                <tr>
                    <td><b>Skupaj: </b></td>
                    <td>{{ "10" }}</td>
                    <td>{{ "180" }}</td>
                    <td>{{ "20" }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br />
    <div class="row">
        <div class="col-md-offset-9 col-md-1">
            {!! Form::submit('Export to PDF', ['class'=>'btn btn-info']) !!}
        </div>
        <div class="col-md-1" style="padding-left: 33px">
            {!! Form::submit('Export to CSV', ['class'=>'btn btn-info']) !!}
        </div>
    </div>
    <br />
    <br />
    <br />
</div>
@endsection