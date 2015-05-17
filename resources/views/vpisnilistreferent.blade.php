@extends('app')

@section('content')
<div class="container">
    @if($errors->any())
        <ul class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if($tip==0)
    <h2>VPISNI LIST {!! date('Y')."/".(date('Y')+1) !!}</h2>
    <h3>za študente</h3>
    <h4>Fakulteta za raculanistvo in informatiko</h4>
    <hr />
    {!! Form::open(array('action' => array('VpisniListReferentController@vpisi', $tip))) !!}
        <div class="well">
        <div class="row">
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('vstevilka','Vpisna številka') !!}
                    {!! Form::text('vstevilka', $vp, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('imepriimek','Ime in Priimek') !!}
                    {!! Form::text('imepriimek', ucfirst($kand->ime_kandidata)." ".ucfirst($kand->priimek_kandidata), ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('datumrojstva','Datum rojstva') !!}
                    {!! Form::text('datumrojstva', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavarojstva','Država rojstva') !!}
                    {!! Form::select('drzavarojstva', $drzave, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('krajrojstva','Občina rojstva') !!}
                    {!! Form::select('krajrojstva', $obcine, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavljanstvo','Državljanstvo') !!}
                    {!! Form::select('drzavljanstvo', $drzave, null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-1 col-xs-3">
                <div class="form-group-sm">
                    {!! Form::label('spol','Spol') !!}
                    {!! Form::text('spol', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('emso','Emšo') !!}
                    {!! Form::text('emso', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('davcna','Davčna številka') !!}
                    {!! Form::text('davcna', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-3 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('email','e-pošta') !!}
                    {!! Form::text('email', $kand->email_kandidata, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('gsm','Prenosni telefon') !!}
                    {!! Form::text('gsm', null, ['class' => 'form-control']) !!}
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
                    <th>Pošta</th>
                    <th>Občina</th>
                    <th>Država</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('stalno','Stalno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje', 'vstalno') !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::text('naslovstalno', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('postastalno', $poste, null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcinastalno', $obcine, null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzavastalno', $drzave, 0, ['class' => 'form-control']) !!}</td>
                </tr>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('zacasno','Začasno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje', 'vzacasno') !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::text('naslovzacasno', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::select('postazacasno', $poste,null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcinazacasno', $obcine, null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzavazacasno', $drzave, 0, ['class' => 'form-control']) !!}</td>
                </tr>
            </tbody>
        </table>
        </div>
        <br />
        <br />
        <br />


        <h3>PODATKI O VPISU</h3>
        <hr />
        <br />
        <div class="well">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                       {!! Form::label('studiskiprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                       {!! Form::select('studiskiprogram', $studijski_programi, $stdpro, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />


            <div class="row">
                <div class="col-md-3 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('zavod','Zavod') !!}
                        {!! Form::text('zavod', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-3 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('krajizvajanja','Kraj izvajanja') !!}
                        {!! Form::text('krajizvajanja', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <!--
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                       {!! Form::label('studiskiprogram2','Študijski program', ['style' => 'font-weight: bold']) !!}
                       {!! Form::select('studiskiprogram2', $studijski_programi, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group-sm">
                        {!! Form::label('sumz2','Smer/usmeritev/modul/znanstveno področje') !!}
                        {!! Form::text('sumz2', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-3 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('krajizvajanja2','Kraj izvajanja') !!}
                        {!! Form::text('krajizvajanja2', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <br />
            -->

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group-sm">
                        {!! Form::label('vrstastudija','Vrsta študija') !!}
                        {!! Form::select('vrstastudija',$vrste_studija, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-3 col-xs-5">
                    <div class="form-group-sm">
                        {!! Form::label('vrstavpisa','Vrsta vpisa') !!}
                        {!! Form::select('vrstavpisa', $vrste_vpisa ,null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2 col-xs-3">
                    <div class="form-group-sm">
                        {!! Form::label('letnikdodatno','Letnik/dodatno leto') !!}
                        {!! Form::select('letnikdodatno', $letnik , null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('nacin','Način študija') !!}
                        {!! Form::select('nacin',$nacin ,null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('oblika','Oblika študija') !!}
                        {!! Form::select('oblika',$oblik , null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-10">
                    <div class="form-inline">
                        <div class="form-group-sm">
                            <p>Študijsko leto prvega vpisa v ta študijski program</p>
                            {!! date('Y')."/".(date('Y')+1) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br />
        </div>

        <div class="row">
            <div class="col-md-11 col-xs-3">
                <div class="form-group-sm">
                    Datum oddaje
                    {!! date('d.m.Y') !!}
                </div>
            </div>
            <div class="col-md-1">
                {!! Form::submit('Naprej', ['class'=>'btn btn-default']) !!}
            </div>
        </div>
        <br />

        {!! Form::close() !!}
    @elseif($tip==1)
    <h2>VPISNI LIST {!! date('Y')."/".(date('Y')+1) !!}</h2>
    <h3>za študente</h3>
    <h4>Fakulteta za raculanistvo in informatiko</h4>
    <hr />
    {!! Form::open(array('action' => array('VpisniListReferentController@vpisi', $tip))) !!}
        <div class="well">
        <div class="row">
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('vstevilka','Vpisna številka') !!}
                    {!! Form::text('vstevilka', $stud->vpisna_stevilka, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('imepriimek','Ime in Priimek') !!}
                    {!! Form::text('imepriimek', $stud->ime_studenta." ".$stud->priimek_studenta, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('datumrojstva','Datum rojstva') !!}
                    {!! Form::text('datumrojstva', date("d.m.Y", strtotime($stud->datum_rojstva)), ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavarojstva','Država rojstva') !!}
                    {!! Form::select('drzavarojstva', $drzave, $drz, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('krajrojstva','Občina rojstva') !!}
                    {!! Form::select('krajrojstva', $obcine, $obc, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavljanstvo','Državljanstvo') !!}
                    {!! Form::select('drzavljanstvo', $drzave, $drz2, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-1 col-xs-3">
                <div class="form-group-sm">
                    {!! Form::label('spol','Spol') !!}
                    {!! Form::text('spol', $stud->spol, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('emso','Emšo') !!}
                    {!! Form::text('emso', $stud->emso, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('davcna','Davčna številka') !!}
                    {!! Form::text('davcna', $stud->davcna_stevilka, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-3 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('email','e-pošta') !!}
                    {!! Form::text('email', $stud->email_studenta, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('gsm','Prenosni telefon') !!}
                    {!! Form::text('gsm', $stud->prenosni_telefon, ['class' => 'form-control']) !!}
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
                    <th>Pošta</th>
                    <th>Občina</th>
                    <th>Država</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('stalno','Stalno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje', 'vstalno', $v) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::text('naslovstalno', $stud->naslov_stalno, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('postastalno', $poste, $nass, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcinastalno', $obcine, $obcs, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzavastalno', $drzave, $drzs, ['class' => 'form-control']) !!}</td>
                </tr>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('zacasno','Začasno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje', 'vzacasno', !$v) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::text('naslovzacasno', $stud->naslov_zacasno, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::select('postazacasno', $poste, $nasz, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcinazacasno', $obcine, $obcz, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzavazacasno', $drzave, $drzz, ['class' => 'form-control']) !!}</td>
                </tr>
            </tbody>
        </table>
        </div>
        <br />
        <br />
        <br />


        <h3>PODATKI O VPISU</h3>
        <hr />
        <br />
        <div class="well">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                       {!! Form::label('studiskiprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                       {!! Form::select('studiskiprogram', $studijski_programi, $stdpro, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-3 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('zavod','Zavod') !!}
                        {!! Form::text('zavod', $zavod, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-3 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('krajizvajanja','Kraj izvajanja') !!}
                        {!! Form::text('krajizvajanja', $kraj, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group-sm">
                        {!! Form::label('vrstastudija','Vrsta študija') !!}
                        {!! Form::select('vrstastudija',$vrste_studija, $stdvrs, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-3 col-xs-5">
                    <div class="form-group-sm">
                        {!! Form::label('vrstavpisa','Vrsta vpisa') !!}
                        {!! Form::select('vrstavpisa', $vrste_vpisa , $vpvrs, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2 col-xs-3">
                    <div class="form-group-sm">
                        {!! Form::label('letnikdodatno','Letnik/dodatno leto') !!}
                        {!! Form::select('letnikdodatno', $letnik , $let, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('nacin','Način študija') !!}
                        {!! Form::select('nacin',$nacin ,$stdnac, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-2 col-xs-4">
                    <div class="form-group-sm">
                        {!! Form::label('oblika','Oblika študija') !!}
                        {!! Form::select('oblika',$oblik , $stdobl, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-10">
                    <div class="form-inline">
                        <div class="form-group-sm">
                            <p>Študijsko leto prvega vpisa v ta študijski program</p>
                            {!! $leto !!}
                        </div>
                    </div>
                </div>
            </div>
            <br />
        </div>

        <div class="row">
            <div class="col-md-11 col-xs-3">
                <div class="form-group-sm">
                    Datum oddaje
                    {!! date('d.m.Y') !!}
                </div>
            </div>
            <div class="col-md-1">
                {!! Form::submit('Naprej', ['class'=>'btn btn-default']) !!}
            </div>
        </div>
        <br />
    {!! Form::close() !!}
    @endif

</div>
@endsection