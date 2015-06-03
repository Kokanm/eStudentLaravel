@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<!--<div class="panel-heading">Home</div>-->

				<div class="panel-body">
					<h4>UREJANJE IZPITNIH ROKOV za študijsko leto {{ $leto }}, {{ $program }} {{ $letnik }}. letnik</h4>
					<hr/><br/>

					<h4>DODAJ NOV IZPITNI ROK</h4><br/>
					{!! Form::open(array('url' => 'izpitnirokiurejanjeprofesor')) !!}

					{!! Form::hidden('stleto', $stleto2) !!}
					{!! Form::hidden('stprogram', $stprogram2) !!}
					{!! Form::hidden('stletnik', $stletnik2) !!}

				    <div class="form-group">
				        <div class="row">
				            <div class="col-md-7">
				                {!! Form::label('pred','Predmet', ['style' => 'font-weight: bold']) !!}
				                {!! Form::select('pred', $predmeti, 0, ['class' => 'form-control', 'id'=>'predmeti']) !!}
				            </div>
				            <div class="col-md-3">
				            	{!! Form::label('datum','Datum', ['style' => 'font-weight: bold']) !!}
				            	{!! Form::text('datum', '', ['class' => 'datepicker', 'id' => 'datum']) !!}
				            </div>
				        </div>
				        <br/>
                		
                		<div class="row">
                			<div class="col-md-2">
				                {!! Form::label('ura','Ura', ['style' => 'font-weight: bold']) !!}
				                {!! Form::text('ura', '', ['class' => 'form-control', 'id'=>'ura']) !!}
				            </div>
				            <div class="col-md-2">
				                {!! Form::label('predavalnica','Predavalnica', ['style' => 'font-weight: bold']) !!}
				                {!! Form::text('predavalnica', '', ['class' => 'form-control', 'id'=>'predavalnica']) !!}
				            </div>
				            <div class="col-md-6">
				                {!! Form::label('opombe','Opombe', ['style' => 'font-weight: bold']) !!}
				                {!! Form::text('opombe', '', ['class' => 'form-control', 'id'=>'opombe']) !!}
				            </div>
                			{!! Form::submit('Dodaj', ['name'=>'dodajIzpitniRok', 'class'=>'btn btn-success', 'style'=>'margin-top: 25px; margin-left: 15px;']) !!}
                		</div>
				    </div>
				    {!! Form::close() !!}

				    <br/>
				    

					@if(!empty($izpitniRoki))
						<br/>
						<hr/>
				        <br/>
						<h4>UREDI / ODSTRANI IZPITNI ROK</h4><br/>
						@for ($i = 0; $i < count($izpitniRoki); $i++)
							{!! Form::open(array('url' => 'izpitnirokiurejanjeprofesor')) !!}

							{!! Form::hidden('stleto', $stleto2) !!}
							{!! Form::hidden('stprogram', $stprogram2) !!}
							{!! Form::hidden('stletnik', $stletnik2) !!}

						    <div class="form-group">
						        <div class="row">

						        	{!! Form::hidden('id', $izpitniRoki[$i][0]) !!}

						            <div class="col-md-7">
						                {!! Form::label('pred','Predmet', ['style' => 'font-weight: bold']) !!}
						                {!! Form::text('pred', $izpitniRoki[$i][5], ['class' => 'form-control', 'id'=>'predmeti', 'readonly']) !!}
						            </div>
						            <div class="col-md-3">
						            	{!! Form::label('datum','Datum', ['style' => 'font-weight: bold']) !!}
										{!! Form::text('datum', $izpitniRoki[$i][1], ['class' => 'datepicker', 'id' => $izpitniRoki[$i][0]]) !!}
						            </div>
						            <div class="col-md-1">
						            	{!! Form::label('prijavljeni','Prijave', ['style' => 'font-weight: bold']) !!}
						            	<b>{{ $izpitniRoki[$i][6] }}</b>
						            </div>
						            
						        </div>
						        <br/>
		                		
		                		<div class="row">
		                			<div class="col-md-2">
						                {!! Form::label('ura','Ura', ['style' => 'font-weight: bold']) !!}
						                {!! Form::text('ura', $izpitniRoki[$i][2], ['class' => 'form-control', 'id'=>'ura']) !!}
						            </div>
						            <div class="col-md-2">
						                {!! Form::label('predavalnica','Predavalnica', ['style' => 'font-weight: bold']) !!}
						                {!! Form::text('predavalnica', $izpitniRoki[$i][3], ['class' => 'form-control', 'id'=>'predavalnica']) !!}
						            </div>
						            <div class="col-md-6">
						                {!! Form::label('opombe','Opombe', ['style' => 'font-weight: bold']) !!}
						                {!! Form::text('opombe', $izpitniRoki[$i][4], ['class' => 'form-control', 'id'=>'opombe']) !!}
						            </div>
						            {!! Form::submit('Uredi', ['name'=>'urediIzpitniRok', 'class'=>'btn btn-primary', 'style'=>'margin-top: 25px; margin-left: 0px;']) !!}
		                			{!! Form::submit('Odstrani', ['name'=>'odstraniIzpitniRok', 'class'=>'btn btn-danger', 'style'=>'margin-top: 25px; margin-left: 6px;']) !!}
		                		</div>
						    </div>
						    {!! Form::close() !!}
						    <hr/>
						@endfor
					@endif

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
