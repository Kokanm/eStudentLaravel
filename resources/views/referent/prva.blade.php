@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('referent/uvoz_podatkov') }}">Uvoz podatkov o sprejetih kandidatih</a></li>
	<li><a href="{{ url('vpis') }}">Zajem vpisnega lista</a></li>
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
				<!--<div class="panel-heading">Home</div>-->

				<div class="panel-body">
					Pozdravljen referent {{ $email }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

