@extends('app')

@section('content')
<div class="container">
    <h2>VPISNI LIST <b>{!! date('Y')."/".(date('Y')+1) !!}</b></h2>
    <h3>za študente</h3>
    <h4>Fakulteta za račulanistvo in informatiko</h4>
    <hr />
    <div class="row">
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Vpisna številka</p>
                <b>{!! $vse['vpisnastevilka'] !!}</b>
            </div>
        </div>
        <div class="col-md-3 col-xs-4">
            <div class="form-group-sm">
                <p>Priimek in ime</p>
                <b>{!! $vse['priimekime'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Datum rojstva</p>
                <b>{!! $vse['datum'] !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Država, občina rojstva</p>
                <b>{!! $vse['drzava'] !!}</b>
            </div>
        </div>
        <div class="col-md-4 col-xs-6">
            <div class="form-group-sm">
                <p>Državljanstvo</p>
                <b>{!! $vse['drzavljanstvo'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-2 col-xs-3">
            <div class="form-group-sm">
                <p>Spol</p>
                <b>{!! $vse['spol'] !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-5">
            <div class="form-group-sm">
                <p>Emšo</p>
                <b>{!! $vse['emso'] !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Davčna številka</p>
                <b>{!! $vse['ds'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-3 col-xs-5">
            <div class="form-group-sm">
                <p>e-pošta</p>
                <b>{!! $vse['email'] !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Prenosni telefon</p>
                <b>{!! $vse['gsm'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Vročanje</th>
                <th>Naslov</th>
                <th>Država, občina</th>
            </tr>
        </thead>izpi

        <tbody>
            <tr>
                <td><b>Stalno bivališče</b></td>
                <td>{!! Form::checkbox('vrocanje', 1, $tr1, ['disabled']) !!}</td>
                <td>{!! $vse['naslov1'] !!}</td>
                <td>{!! $vse['obcina1'] !!}</td>
            </tr>
            <tr>
                <td><b>Začasno bivališče</b></td>
                <td>{!! Form::checkbox('vrocanje', 2, $tr2, ['disabled']) !!}</td>
                <td>{!! $vse['naslov2'] !!}</td>
                <td>{!! $vse['obcina2'] !!}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <br />
    <br />
    <br />
    <br />

    <h3>PODATKI O VPISU</h3>
    <hr />
    <br />
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
               <p>Študijski program</p>
               <b>{!! $vse['program'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-3 col-xs-4">
            <div class="form-group-sm">
                <p>Kraj izvajanja</p>
                <b>{!! $vse['krajizvajanja'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-7">
            <div class="form-group-sm">
                <p>Vrsta študija</p>
                <b>{!! $vse['vrstastudija'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-3 col-xs-5">
            <div class="form-group-sm">
                <p>Vrsta vpisa</p>
                <b>{!! $vse['vrstevpisa'] !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group-sm">
                <p>Letnik/dodatno leto</p>
                <b>{!! $vse['letnik'] !!}</b>
            </div>
        </div>
        <div class="col-md-2 col-xs-4">
            <div class="form-group-sm">
                <p>Način in oblika študija</p>
                <b>{!! $vse['nacinoblika'] !!}</b>
            </div>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-md-10">
            <div class="form-inline">
                <div class="form-group-sm">
                    <p>Študijsko leto prvega vpisa v ta študijski program</p>
                    <b>{!! $vse['prvivpis'] !!}</b>
                </div>
            </div>
        </div>
    </div>
    <br />
    <hr />

    <h4>PRILOGA 1: PREDMETNIK</h4>
    <div class="row">
        <div class="col-md-7">
            <div class="form-group-sm">
                <b>Študijski program</b>
                <p>{!! $studijski_program !!}</p>
            </div>
        </div>
    </div>
    <br />
    <table class="table">
        <thead>
            <tr>
                <th>Učitelj</th>
                <th>Učna enota</th>
                <th>Število KT</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<count($predmeti); $i++)
                <tr>
                    <td>{{ $predmeti[$i][0]. ', '. $predmeti[$i][1] }}</td>
                    <td>{{ $predmeti[$i][2] }}</td>
                    <td>{{ $predmeti[$i][3] }}</td>
                </tr>
            @endfor
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{ $sum }}</td>
                </tr>
        </tbody>
    </table>
    <br />

    <div class="col-md-offset-10 col-md-2">
        @if(array_key_exists('potrdi', $vse))
           {!! Form::open(array('action' => array('PotrditevVpisaController@potrdi', $vse['vpisnastevilka']))) !!}
                {!! Form::submit('Potrdi', ['class' => 'btn btn-primary']) !!}
           {!! Form::close() !!}
        @else
           {!! Form::button('Tiskaj', ['class' => 'btn btn-info buttonClass', 'onClick' => 'window.print()']) !!}
        @endif
    </div>

    <br />
    <hr />

</div>
@endsection