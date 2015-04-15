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
    {!! Form::open(array('url'=>'vpis')) !!}
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
                    {!! Form::text('imepriimek', $kand->ime_kandidata." ".$kand->priimek_kandidata, ['class' => 'form-control']) !!}
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
                    <th>Poštna št.</th>
                    <th>Država</th>
                    <th>Občina</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('stalno','Stalno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje') !!}</td>
                    <td class="col-md-3 col-xs-4">{!! Form::text('naslovstalno', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::text('posta', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzavastalno', $drzave, 0, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcinastalno', $obcine, null, ['class' => 'form-control']) !!}</td>
                </tr>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('zacasno','Začasno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje') !!}</td>
                    <td class="col-md-3 col-xs-4">{!! Form::text('naslov2', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::text('posta2', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzava2', $drzave, 0, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcina2', $obcine, null, ['class' => 'form-control']) !!}</td>
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
                       {!! Form::select('studiskiprogram', $studijski_programi, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <!--
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group-sm">
                        {!! Form::label('sumz','Smer/usmeritev/modul/znanstveno področje') !!}
                        {!! Form::text('sumz', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            -->

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

        <!--
        <h4>PODATKI VZPOREDNEM ŠTUDIJU</h4>
        <div class="row">
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('zavod','Zavod') !!}
                    {!! Form::text('zavod', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-4 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('letnikdodatno2','Smer/usmeritev/modul/znanstveno področje') !!}
                    {!! Form::text('letnikdodatno2', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-7">
                <div class="form-group-sm">
                    {!! Form::label('studijskiprogram3','Študijski program') !!}
                    {!! Form::text('studijskiprogram3', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-7">
                <div class="form-group-sm">
                    {!! Form::label('vrstastudija2','Vrsta študija') !!}
                    {!! Form::text('vrstastudija2', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />
        -->

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


        <!--
        <h4>PRILOGA 2: PREDHODNO DOSEŽENA IZOBRAZBA</h4>
        <div class="row">
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('zavod2','Zavod') !!}
                    {!! Form::text('zavod2', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('kraj','Kraj') !!}
                    {!! Form::text('kraj', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('drzava','Država') !!}
                    {!! Form::text('drzava', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('program','Program') !!}
                    {!! Form::text('program', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('letozakljucka','Leto zaključka') !!}
                    {!! Form::text('letozakljucka', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('uspeh','Uspeh') !!}
                    {!! Form::text('uspeh', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-4 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('snsi','Smer/naziv strokone izobrazbe') !!}
                    {!! Form::text('snsi', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('nacinkoncanja','Način končanja srednje šole') !!}
                    {!! Form::text('nacinkoncanja', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-4 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('klasius','KLASIUS SRV') !!}
                    {!! Form::text('klasius', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-5">
                <div class="form-group-sm">
                    {!! Form::label('najvisjaizobrazba','Najvišja dosežena izobrazba') !!}
                    {!! Form::text('najvisjaizobrazba', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />

        <h4>PRILOGA 3: DODATNI PODATKI</h4>
        <br />
        <div class="form-horizontal">
            <div class="row">
                <div class="form-group-sm">
                    {!! Form::label('tutor','Tutor', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('tutor', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="form-group-sm">
                    {!! Form::label('steviloobrokov','Število obrokov za plačilo šolni', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('steviloobrokov', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="form-group-sm">
                    {!! Form::label('trr','TRR za plačilo štipendije', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('trr', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <br />
        -->
    @elseif($tip==1)
    <h2>VPISNI LIST {!! date('Y')."/".(date('Y')+1) !!}</h2>
    <h3>za študente</h3>
    <h4>Fakulteta za raculanistvo in informatiko</h4>
    <hr />
    {!! Form::open(array('url'=>'vpis')) !!}
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
                    {!! Form::text('datumrojstva', $stud->datum_rojstva, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavarojstva','Država rojstva') !!}
                    {!! Form::select('drzavarojstva', $drzave, $, ['class' => 'form-control']) !!}
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
                    <th>Poštna št.</th>
                    <th>Država</th>
                    <th>Občina</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('stalno','Stalno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje') !!}</td>
                    <td class="col-md-3 col-xs-4">{!! Form::text('naslovstalno', $stud->naslov_stalno, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::text('posta', $stud, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzavastalno', $drzave, 0, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcinastalno', $obcine, null, ['class' => 'form-control']) !!}</td>
                </tr>
                <tr>
                    <td class="col-md-2 col-xs-3">{!! Form::label('zacasno','Začasno bivališče', ['style' => 'font-weight:bold']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::radio('vrocanje') !!}</td>
                    <td class="col-md-3 col-xs-4">{!! Form::text('naslov2', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-1 col-xs-2">{!! Form::text('posta2', null, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('drzava2', $drzave, 0, ['class' => 'form-control']) !!}</td>
                    <td class="col-md-2 col-xs-3">{!! Form::select('obcina2', $obcine, null, ['class' => 'form-control']) !!}</td>
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
                       {!! Form::select('studiskiprogram', $studijski_programi, null, ['class' => 'form-control']) !!}
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

    @endif

    {!! Form::close() !!}
</div>
@endsection