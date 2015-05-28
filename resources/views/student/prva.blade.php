@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('vpis') }}">Zajem vpisnega lista</a></li>
	<li><a href="{{ url('tisks') }}">Izpis vpisnega lista</a></li>
	<li><a href="{{ url('prijava') }}">Prijava na izpit</a></li>
	<li><a href="{{ url('kartotecniS') }}">Kartotečni list</a></li>
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
					    Pozdravljen študent {{ $email }}
                    @endif
				</div>

			</div>
		</div>
	</div>
</div>
@endsection

