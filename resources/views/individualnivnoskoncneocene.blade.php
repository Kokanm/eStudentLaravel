@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body" style="margin-left: 15px;">
                    <h4>VPIS KONČNE OCENE za študenta: {{ $student_ime }} {{ $student_priimek }}</h4>
                    <hr/><br/>

                    <h4>VNESI OCENO ZA POLJUBEN IZPITNI ROK</h4><br/>
                        {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneController@vnesi', $vp))) !!}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    {!! Form::label('stleto','Studijsko leto', ['style' => 'font-weight: bold']) !!}
                                    {!! Form::select('stleto', $leto, 0, ['class' => 'form-control', 'id'=>'leto']) !!}
                                </div>
                                <div class="col-md-5">
                                    {!! Form::label('stprogram','Študijski program', ['style' => 'font-weight: bold']) !!}
                                    {!! Form::select('stprogram', $program, 0, ['class' => 'form-control', 'id'=>'program']) !!}
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('stletnik','Letnik', ['style' => 'font-weight: bold']) !!}
                                    {!! Form::select('stletnik', $letnik, 0, ['class' => 'form-control', 'id'=>'letnik']) !!}
                                </div>
                                {!! Form::submit('Izberi', ['name'=>'izberi', 'class'=>'btn btn-primary', 'style'=>'margin-top: 25px; margin-left: 15px;']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    <br/>
                    <hr/><br/>

                    @if(!empty($izpiti))
                        <h4>VNESI OCENO ZA IZPITNE ROKE, NA KATERE JE ŠTUDENT PRIJAVLJEN</h4>

                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <!--<th>Šifra</th>-->
                                <th>Predmet</th>
                                <th>KT</th>
                                <th>Izvajalci</th>
                                <th>Polaganje</th>
                                <th>(letos)</th>
                                <th>Datum</th>
                                <th>Ura</th>
                                <th>Ocena</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        @for ($i = 0; $i < count($izpiti); $i++)
                            {!! Form::open(array('action' => array('IndividualniVnosKoncneOceneController@vnesi', $vp))) !!}
                                {!! Form::hidden('id', $izpiti[$i][0]) !!}
                                <tr>
                                    <td>{{ $izpiti[$i][1] }} {{ $izpiti[$i][2] }}</td>
                                    <td>{{ $izpiti[$i][3] }}</td>
                                    <td>{{ $izpiti[$i][4] }}</td>
                                    <th>{{ $izpiti[$i][7] }}.</th>
                                    <th>{{ $izpiti[$i][8] }}.</th>
                                    <td>{{ $izpiti[$i][5] }}</td>
                                    <td>{{ $izpiti[$i][6] }}</td>
                                    <td>{!! Form::text('ocena', '', ['class' => 'form-control input-sm', 'id'=>'ocena', 'style'=>'width: 40px; height: 22px;']) !!}</td>
                                    <td>{!! Form::submit('Oceni', ['name'=>'oceni', 'class'=>'btn btn-success btn-xs']) !!}</td>
                                </tr>
                            {!! Form::close() !!}
                        @endfor
                        </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

