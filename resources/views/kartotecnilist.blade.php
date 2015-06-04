@extends('app')

@section('content')
<div class="container">
    {!! Form::open(array('url' => 'kartotecniS')) !!}
    <div class="row">
        <h2 style="padding-left: 14px">{{ $name }}</h2>
    </div>
    <div class="row">
        <div class="col-md-1">
            {!! Form::submit('Vsa polaganja', ['class'=>'btn btn-info '.$active[0], 'name' => 'vsa']) !!}
        </div>
        <div class="col-md-1" style="padding-left: 33px">
            {!! Form::submit('Zadnja polaganja', ['class'=>'btn btn-info '.$active[1], 'name' => 'zadnja']) !!}
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
    @for($i=0; $i<count($studijski_program); $i++)
        <div class="row">
            <h3>{{ ($i+1).". ".substr($studijski_programi[$i+1], 7) }}</h3>
        </div>
        @for($j=0; $j<count($heading[$i]); $j++)
            <div class="row">
                <div class="col-md-11">
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
                                <td>{{ $heading[$i][$j][0] }}</td>
                                <td>{{ $heading[$i][$j][1].". letnik" }}</td>
                                <td>{{ $heading[$i][$j][2] }}</td>
                                <td>{{ $heading[$i][$j][3] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-11">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;"></th>
                                <th style="width: 10%;">Šifra</th>
                                <th style="width: 25%;">Predmet</th>
                                <th style="width: 15%;">Ocenil</th>
                                <th style="width: 10%;">Letnik</th>
                                <th style="width: 13%;">Datum</th>
                                <th colspan="2" style="width: 12%;">Št. polaganj</th>
                                <th style="width: 5%;">KT</th>
                                <th style="width: 5%;">Ocena</th>
                            </tr>
                        </thead>
                        <tbody>

                        {{--*/ $suma = 0 /*--}}
                        {{--*/ $nare = 0 /*--}}
                        {{--*/ $stkt = 0 /*--}}
                            @for($k=0; $k<$stpredmetov[$i][$j]; $k++)
                                <tr>
                                    @if($k != 0 && $izpiti[$i][$j][$k][0] == $temp)
                                        <td class="col-md-offset-1 col-md-1 col-xs-3"></td>
                                        <td class="col-md-offset-1 col-md-2 col-xs-3"></td>
                                        <td class="col-md-offset-1 col-md-2 col-xs-3"></td>
                                    @else
                                        <td>{{ ($k+1)."." }}</td>
                                        <td>{{ $izpiti[$i][$j][$k][0] }}</td>
                                        <td>{{ $izpiti[$i][$j][$k][1] }}</td>
                                    @endif
                                    <td>{{ $izpiti[$i][$j][$k][2] }}</td>
                                    <td>{{ $izpiti[$i][$j][$k][3] }}</td>
                                    <td>{{ $izpiti[$i][$j][$k][4] }}</td>
                                    <td>{{ $izpiti[$i][$j][$k][5] }}</td>
                                    <td>{{ $izpiti[$i][$j][$k][6] }}</td>
                                    <td>{{ $izpiti[$i][$j][$k][7] }}</td>
                                    <td>{{ $izpiti[$i][$j][$k][8] }}</td>
                                </tr>
                                {{--*/ $temp = $izpiti[$i][$j][$k][0] /*--}}
                                @if($izpiti[$i][$j][$k][8] > 5)
                                    {{--*/ $suma += $izpiti[$i][$j][$k][8] /*--}}
                                    {{--*/ $nare += 1 /*--}}
                                    {{--*/ $stkt += $izpiti[$i][$j][$k][7] /*--}}
                                @endif
                            @endfor
                            <tr>
                                <td colspan="3" style="font-weight: bold; font-size: medium; padding-left: 10px">Število opravljenih izpitov: </td>
                                <td>{{ $nare }}</td>
                                <td colspan="4" style="font-weight: bold; font-size: medium; padding-left: 10px">Vsota KT in povprečno oceno:</td>
                                <td>{{ $stkt }}</td>
                                @if($nare != 0)
                                    <td>{{ round($suma/$nare, 3)}}</td>
                                @else
                                    <td>{{ 0 }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    <hr />
                    <br />
                </div>
            </div>
        @endfor
    @endfor
    <br />
    <hr />
    <br />
    <div class="row">
        <h3>Skupno povprečno oceno in število kreditnih točk</h3>
        <br />
        @for($i=0; $i<count($studijski_program); $i++)
        <div class="row">
            <h4>{{ ($i+1).". ".substr($studijski_programi[$i+1], 7) }}</h4>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Študijsko leto</th>
                    <th>Število opravljenih izpitov</th>
                    <th>Kreditne točke</th>
                    <th>Skupno povprečje</th>
                </tr>
            </thead>
            <tbody>
            {{--*/ $sumaP = 0 /*--}}
            {{--*/ $sumaN = 0 /*--}}
            {{--*/ $sumaK = 0 /*--}}
                @for($j=0; $j<count($heading[$i]); $j++)
                    <tr>
                        <td>{{ $heading[$i][$j][0]}}</td>
                        <td>{{ $skupnare[$i][$j] }}</td>
                        <td>{{ $skupkt[$i][$j] }}</td>
                        <td>{{ $povp[$i][$j] }}</td>
                        {{--*/ $sumaP += $povp[$i][$j] /*--}}
                        {{--*/ $sumaN += $skupnare[$i][$j] /*--}}
                        {{--*/ $sumaK += $skupkt[$i][$j] /*--}}
                        @if($heading[$i][$j][2] == "Ponavljanje letnika")
                            {{--*/ $sumaP -= $povp[$i][$j-1] /*--}}
                            {{--*/ $sumaN -= $skupnare[$i][$j-1] /*--}}
                            {{--*/ $sumaK -= $skupkt[$i][$j-1] /*--}}
                        @endif
                    </tr>
                @endfor
                <tr>
                    <td><b>Skupaj: </b></td>
                    <td>{{ $sumaN }}</td>
                    <td>{{ $sumaK }}</td>
                    <td>{{ round($sumaP/count($heading[$i]), 3) }}</td>
                </tr>
            </tbody>
        </table>
        @endfor
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
    {!! Form::close() !!}
</div>
@endsection