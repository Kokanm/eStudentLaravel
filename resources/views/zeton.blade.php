@extends('app')

@section('content')
<div class="container">
	@if(!empty($porabljeniZetoni))
		<h3>PORABLJENI ŽETONI</h3><br/>
		<table class="table table-hover">
		<thead>
            <tr>
                <!--<th>Šifra</th>-->
                <th>Študijsko leto</th>
                <th>Letnik</th>
                <th>Študijski program</th>
                <th>Oblika študija</th>
                <th>Način študija</th>
                <th>Vrsta študija</th>
                <th>Prosta izbira predmetov</th>
            </tr>
        </thead>
        <tbody>
		@for ($i = 0; $i < count($porabljeniZetoni); $i++)
			<tr>
                <!--<td>{{ $porabljeniZetoni[$i][7] }}</td>-->
				<td>{{ $porabljeniZetoni[$i][0] }}</td>
				<td>{{ $porabljeniZetoni[$i][1] }}</td>
				<td>{{ $porabljeniZetoni[$i][2] }}</td>
				<td>{{ $porabljeniZetoni[$i][3] }}</td>
				<td>{{ $porabljeniZetoni[$i][4] }}</td>
				<td>{{ $porabljeniZetoni[$i][5] }}</td>
				<td>{{ $porabljeniZetoni[$i][6] }}</td>
			</tr>
		@endfor
		</tbody>
		</table>
        <br/>
		<hr/>
        <br/>
	@endif
	@if(!empty($neporabljeniZetoni))
		<h3>NEPORABLJENI ŽETONI</h3><br/>
		@for ($i = 0; $i < count($neporabljeniZetoni); $i++)
            {!! Form::open(array('action' => array('ReferentController@dodajZeton', $vp))) !!}
            <div class="form-group">
                <div class="row">

                    {!! Form::hidden('idzetona', $neporabljeniZetoni[$i][7]) !!}

                    <div class="col-md-2">
                        {!! Form::label('stleto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('stleto', $leto, $neporabljeniZetoni[$i][0], ['class' => 'form-control', 'id'=>'leto']) !!}
                        <!--{!! Form::text('stleto', $neporabljeniZetoni[$i][0], ['class' => 'form-control', 'id'=>'leto']) !!}-->
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('stletnik','Letnik', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('stletnik', $letnik, $neporabljeniZetoni[$i][1], ['class' => 'form-control', 'id'=>'letnik']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('stprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('stprogram', $program, $neporabljeniZetoni[$i][2], ['class' => 'form-control', 'id'=>'program']) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('prostaIzbira','Prosta izbira', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('prostaIzbira', $izbira, $neporabljeniZetoni[$i][6], ['class' => 'form-control', 'id'=>'izbira']) !!}
                    </div>
                </div>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-2">
                        {!! Form::label('oblikaStudija','Oblika študija', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('oblikaStudija', $oblika, $neporabljeniZetoni[$i][3], ['class' => 'form-control', 'id'=>'oblika']) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('nacinStudija','Način študija', ['style' => 'font-weight: bold']) !!}
                        {!! Form::select('nacinStudija', $nacin, $neporabljeniZetoni[$i][4], ['class' => 'form-control', 'id'=>'nacin']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('vrstaVpisa','Vrsta vpisa', ['style' => 'font-weight: bold']) !!}
                       {!! Form::select('vrstaVpisa', $vrsta, $neporabljeniZetoni[$i][5], ['class' => 'form-control', 'id'=>'vrsta']) !!}
                    </div>
                    {!! Form::submit('Odstrani žeton', ['name'=>'odstrani', 'class'=>'btn btn-danger', 'style' => 'margin-top: 25px; margin-left: 15px;']) !!}
                    {!! Form::submit('Uredi žeton', ['name'=>'uredi', 'class'=>'btn btn-primary', 'style' => 'margin-top: 25px; margin-left: 15px;']) !!}
                </div>
            </div>
            {!! Form::close() !!}
            <br/><br/>
        @endfor
        <br/>
		<hr/>
        <br/>
	@endif
	<h3>DODAJ NOV ŽETON</h3><br/>
	{!! Form::open(array('action' => array('ReferentController@dodajZeton', $vp))) !!}
	<div class="form-group">
        <div class="row">
            <div class="col-md-2">
                {!! Form::label('stleto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('stleto', $leto, 0, ['class' => 'form-control', 'id'=>'leto']) !!}
            </div>
            <div class="col-md-2">
                {!! Form::label('stletnik','Letnik', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('stletnik', $letnik, 0, ['class' => 'form-control', 'id'=>'letnik']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::label('stprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('stprogram', $program, 0, ['class' => 'form-control', 'id'=>'program']) !!}
            </div>
            <div class="col-md-2">
                {!! Form::label('prostaIzbira','Prosta izbira', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('prostaIzbira', $izbira, 0, ['class' => 'form-control', 'id'=>'izbira']) !!}
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-2">
                {!! Form::label('oblikaStudija','Oblika študija', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('oblikaStudija', $oblika, 0, ['class' => 'form-control', 'id'=>'oblika']) !!}
            </div>
            <div class="col-md-2">
                {!! Form::label('nacinStudija','Način študija', ['style' => 'font-weight: bold']) !!}
                {!! Form::select('nacinStudija', $nacin, 0, ['class' => 'form-control', 'id'=>'nacin']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::label('vrstaVpisa','Vrsta vpisa', ['style' => 'font-weight: bold']) !!}
               {!! Form::select('vrstaVpisa', $vrsta, 0, ['class' => 'form-control', 'id'=>'vrsta']) !!}
            </div>
            {!! Form::submit('Dodaj', ['name'=>'dodaj', 'class'=>'btn btn-success', 'style'=>'margin-top: 25px; margin-left: 15px;']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection

