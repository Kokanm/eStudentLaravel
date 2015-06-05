@extends('app')

@section('content')
<div class="container">

    <div class="row">
        <h2 style="padding-left: 14px">{{ $name }}</h2>
    </div>
    {!! Form::open(array('action' => array('KartotecniListReferentController@gumb', $vpisna))) !!}
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
    {!! Form::close() !!}
    <hr />
    <br />
    @for($i=0; $i<count($studijski_program); $i++)
        <div class="row">
            <div class="col-md-offset-3">
                @if(count($studijski_program) == 1)
                    <h2 style="margin-bottom: 50px">{{ "1. ".substr($studijski_programi[$glupost], 7) }}</h2>
                @else
                    <h2 style="margin-bottom: 50px">{{ ($i+1).". ".substr($studijski_programi[$i+1], 7) }}</h2>
                @endif
            </div>
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
                                <th style="width: 6%;">Št. letos</th>
                                <th style="width: 6%;">Št. skupaj</th>
                                <th style="width: 5%;">KT</th>
                                <th style="width: 5%;">Ocena</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($k=0; $k<$stpredmetov[$i][$j]; $k++)
                                <tr>
                                    @if($k != 0 && $izpiti[$i][$j][$k][0] == $temp)
                                        <td class="col-md-offset-1 col-md-1 col-xs-3" style="border-top: none;"></td>
                                        <td class="col-md-offset-1 col-md-2 col-xs-3" style="border-top: none;"></td>
                                        <td class="col-md-offset-1 col-md-2 col-xs-3" style="border-top: none;"></td>
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
                            @endfor
                            <tr>
                                <td colspan="3" style="font-weight: bold; font-size: medium; padding-left: 10px">Število opravljenih izpitov: </td>
                                <td>{{ $skupnare[$i][$j] }}</td>
                                <td colspan="4" style="font-weight: bold; font-size: medium; padding-left: 10px">Vsota KT in povprečno oceno:</td>
                                <td>{{ $skupkt[$i][$j] }}</td>
                                <td>{{ $povp[$i][$j] }}</td>
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
    <br />
    <div class="row">
        <h3>Skupno povprečno oceno in število kreditnih točk</h3>
        <br />
        @for($i=0; $i<count($studijski_program); $i++)
        <div class="row">
            @if(count($studijski_program) == 1)
                <h4>{{ "1. ".substr($studijski_programi[$glupost], 7) }}</h4>
            @else
                <h4>{{ ($i+1).". ".substr($studijski_programi[$i+1], 7) }}</h4>
            @endif
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
                @for($j=0; $j<count($heading[$i]); $j++)
                    <tr>
                        <td>{{ $heading[$i][$j][0]}}</td>
                        <td>{{ $skupnare[$i][$j] }}</td>
                        <td>{{ $skupkt[$i][$j] }}</td>
                        <td>{{ $povp[$i][$j] }}</td>
                    </tr>
                @endfor
                <tr>
                    <td><b>Skupaj: </b></td>
                    <td>{{ $povse[$i][0] }}</td>
                    <td>{{ $povse[$i][1] }}</td>
                    <td>{{ $povse[$i][2] }}</td>
                </tr>
            </tbody>
        </table>
        @endfor
    </div>

    <br />
      <div class="row">
      {!! Form::open( array( 'url' => 'export' )) !!}
          {!! Form::hidden( 'html' , $html) !!}
          {!! Form::hidden( 'fname' , "kartotecni" ) !!}
          <div class="col-md-offset-9 col-xs-offset-1 col-md-1 col-xs-2">
              {!! Form::submit('Export to PDF', ['name'=>'PDF','class'=>'btn btn-info']) !!}
          </div>
          <div class="col-md-1 col-xs-1 col-xs-2" style="padding-left: 33px">
              {!! Form::submit('Export to CSV', ['name'=>'CSV','class'=>'btn btn-info']) !!}
          </div>
      {!! Form::close() !!}
      </div>
    <br />
    <br />
    <br />

</div>
@endsection