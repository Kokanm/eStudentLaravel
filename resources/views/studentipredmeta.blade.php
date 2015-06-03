@extends('app')
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
		<h3>{{ $naslov }}</h3>
		<br><br>
        <table class="table">
            <tr>
                <th>#</th>
                <th>Vpisna številka</th>
                <th>Priimek in ime</th>
                <th>Vrsta vpisa</th>
            </tr>
            @for($row=0; $row<count($students); $row++)
                <tr>
                    <td>{{ $row+1 }}</td>
                    <td>{{ $students[$row][0] }}</td>
                    <td>{{ $students[$row][1] }}</td>
                    <td>{{ $students[$row][2] }}</td>
                </tr>
            @endfor
        </table>
		<br>
		@if(!isset($izvoz))
			Izvoz:
			<br>
			{!! Form::open(array('url' => 'seznamPredmetIzvoz')) !!}
				{!! Form::hidden('predmet', $stHidden[0]) !!}
				{!! Form::hidden('program', $stHidden[1]) !!}
				{!! Form::hidden('letnik', $stHidden[2]) !!}
				{!! Form::hidden('leto', $stHidden[3]) !!}
				{!! Form::hidden('naslov', $naslov) !!}
				{!! Form::submit('Pdf', ['class' => 'btn btn-primary']) !!}
			{!! Form::close() !!}
		@endif
    </div>
</div>
@endsection