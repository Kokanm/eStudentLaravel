@extends('app')

@section('content')
<div class="container">
    <h4>PRILOGA 1: PREDMETNIK</h4>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group-sm">
                        <b>Študijski program</b>
                        <p>{!! $studijski_program !!}</p>
                    </div>
                </div>
            </div>
            <br />
            <table class="table">
                <thead>
                    <tr>
                        <th>Učitelj</th>
                        <th>Učna enota</th>
                        <th>Število KT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($predmeti as $pre)
                        <tr>
                            <td>Markoski</td>
                            <td>{{ $pre }}</td>
                            <td>6</td>
                        </tr>
                    @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td>60</td>
                        </tr>
                </tbody>
            </table>
            <br />

</div>
@endsection