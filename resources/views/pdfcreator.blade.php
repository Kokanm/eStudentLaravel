@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
	table {
        border-collapse: collapse;
        margin-top: 30px;
    }

    table, th, td {
        border: 1px solid black;
    }
    form{
        display: none;
    }
	</style>

</head>
<body>
{!! html_entity_decode($data) !!}

</body>
</html>
@endsection