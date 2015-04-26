@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('referent/uvoz_podatkov') }}">Uvoz kandidatov</a></li>
	<!--<li><a href="{{ url('vpis') }}">Zajem vpisnega lista</a></li>-->
	<li><a href="{{ url('find') }}">Izpis vpisnega lista</a></li>
	<li><a href="{{ url('potrdi') }}">Potrditev vpisa</a></li>
	<li><a href="{{ url('/') }}">Potrdilo o vpisu</a></li>

</ul>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">UVOZ KANDIDATOV</div>

				<div class="panel-body">
					@if(count($uvozeno))
						{!! $uvozeno !!}
					@endif


					{!! Form::open(array('url' => 'referent/uvoz_podatkov', 'files' => true)) !!}
						<div class="form-group">
							{!! Form::label('datoteka', 'Izberite datoteko za uvoz:') !!}
							{!! Form::file('datoteka', null, ['class' => 'form-control']) !!}
							<!--{!! Form::text('datoteka', null, ['class' => 'form-control']) !!}-->
						</div>

						<br/>
						<div class="form-group">
							<div class="col-md-2 col-md-offset-0">
							
								{!! Form::submit('Uvozi', ['class' => 'btn btn-primary form-control']) !!}
							
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

