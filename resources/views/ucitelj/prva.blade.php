@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('find') }}">Iskanje študenta</a></li>
    <li><a href="{{ url('izpitnirokiprofesor') }}">Izpitni roki</a></li>
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Izpisi <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ url('prijave') }}">Izpis prijavljenih</a></li>
            <li><a href="{{ url('rezultati') }}">Izpis rezultatov</a></li>
            <li><a href="{{ url('ocene') }}">Izpis ocene</a></li>
        </ul>
    </li>
</ul>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<!--<div class="panel-heading">Home</div>-->
				<div class="panel-body">
				    @if (Session::has('message'))
                        <b style="color:red; font-size: large;">{{ Session::get('message') }}</b>
                    @else
					    Pozdravljen Profesor {{ $email }}
                    @endif
				</div>

			</div>
		</div>
	</div>
</div>
@endsection
