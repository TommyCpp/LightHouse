@extends('layout')

@section('content')
    <section>
        <div class="section-head">
            <ol class="breadcrumb">
                <li class="active">
                    会场名额限制
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(['url'=>'/delegation-seat-exchange-rule'])}}
                    @foreach($committees as $committee)
                        <div class="form-group">
                            {{Form::label($committee->abbreviation,$committee->abbreviation)}}
                            {{Form::text($committee->abbreviation,$committee->limit,['class'=>'form-control'])}}
                        </div>
                    @endforeach
                    {{Form::submit("现在提交")}}
                </div>
            </div>
        </div>
    </section>
@endsection