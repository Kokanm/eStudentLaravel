@extends('app')

@section('content')
<div class="container">
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
                    {!! Form::text('vstevilka', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('priimekime','Priimek in ime') !!}
                    {!! Form::text('priimekime', null, ['class' => 'form-control']) !!}
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
            <div class="col-md-2 col-xs-4">
                <div class="form-group-sm">
                    {!! Form::label('krajrojstva','Kraj rojstva') !!}
                    {!! Form::text('krajrojstva', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavarojstva','Država, občina rojstva') !!}
                    {!! Form::text('drzavarojstva', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-6">
                <div class="form-group-sm">
                    {!! Form::label('drzavljanstvo','Državljanstvo') !!}
                    {!! Form::text('drzavljanstvo', null, ['class' => 'form-control']) !!}
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
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
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
                    <th>Država, občina</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{!! Form::label('stalno','Stalno bivališče') !!}</td>
                    <td>{!! Form::radio('vrocanje') !!}</td>
                    <td>{!! Form::text('naslovstalno', null, ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('obcinastalno', null, ['class' => 'form-control']) !!}</td>
                </tr>
                <tr>
                    <td>{!! Form::label('zacasno','Začasno bivališče') !!}</td>
                    <td>{!! Form::radio('vrocanje') !!}</td>
                    <td>{!! Form::text('naslov2', null, ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('obcina2', null, ['class' => 'form-control']) !!}</td>
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
                <div class="col-md-7">
                    <div class="form-group-sm">
                        {!! Form::label('sumz','Smer/usmeritev/modul/znanstveno področje') !!}
                        {!! Form::text('sumz', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
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
                        {!! Form::text('vrstastudija', null, ['class' => 'form-control']) !!}
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
                        {!! Form::label('nacinoblika','Način in oblika študija') !!}
                        {!! Form::text('nacinoblika', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-10">
                    <div class="form-inline">
                        <div class="form-group-sm">
                            {!! Form::label('letoprvegavpisa','Študijsko leto prvega vpisa v ta študijski program') !!}
                            {!! Form::text('letoprvegavpisa', null, ['class' => 'form-control']) !!}
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

        <div class="row">
            <div class="col-md-2 col-xs-3">
                <div class="form-group-sm">
                    {!! Form::label('datumoddaje','Datum oddaje') !!}
                    {!! Form::text('datumoddaje', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <br />
        -->

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
        {!! Form::submit('Naprej') !!}
    {!! Form::close() !!}
</div>
@endsection