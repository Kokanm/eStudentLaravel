<div class="row">
    <div class="col-md-10 col-md-offset-1">
		<h3>{{ $naslov }}</h3>
		<br />
		<br />
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
		<br />
    </div>
</div>