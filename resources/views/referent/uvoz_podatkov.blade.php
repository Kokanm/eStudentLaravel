@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('referent/uvoz_podatkov') }}">Uvoz podatkov o sprejetih kandidatih</a></li>
	<li><a href="{{ url('/') }}">Zajem vpisnega lista</a></li>
	<li><a href="{{ url('/') }}">Izpis vpisnega lista</a></li>
	<li><a href="{{ url('/') }}">Potrditev vpisa</a></li>
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


					{!! Form::open(['url' => 'referent/uvoz_podatkov']) !!}
						<div class="form-group">
							{!! Form::label('datoteka', 'Lokacija datoteke:') !!}
							{!! Form::text('datoteka', null, ['class' => 'form-control']) !!}
						</div>

						<div class="form-group">
							{!! Form::submit('Uvozi', ['class' => 'btn btn-primary form-control']) !!}
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

