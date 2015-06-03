@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('referent/uvoz_podatkov') }}">Uvoz kandidatov</a></li>
	<li><a href="{{ url('vpisa') }}">Zajem vpisnega lista</a></li>
	<li><a href="{{ url('find') }}">Študenti - možnosti</a></li>
	<li><a href="{{ url('potrdi') }}">Potrditev vpisa</a>
	<li><a href="{{ url('izpitniroki') }}">Izpitni roki</a></li>
	<li><a href="{{ url('rezultati') }}">Izpis rezultate</a></li>
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

