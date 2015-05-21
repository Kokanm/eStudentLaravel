@extends('app')

@section('menu')
<ul class="nav navbar-nav">
	<li><a href="{{ url('find') }}">Iskanje študenta</a></li>
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