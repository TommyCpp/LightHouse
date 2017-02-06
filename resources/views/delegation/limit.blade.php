@extends('layout')


@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/select2/select2.css">
@endsection

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    委员会限额
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(['url'=>'committees/limit','class'=>'form'])}}
                    <div class="card">
                        <div class="card-body">
                            @foreach($committees as $committee)
                                <div class='form-group'>
                                    {{Form::text($committee->abbreviation,$committee->limit,['class'=>'form-control'])}}
                                    {{Form::label($committee->abbreviation,$committee->chinese_name." 会场席位限额/每代表团")}}
                                </div>
                            @endforeach
                            <div class="form-group">
                                {{Form::submit('现在提交',['class'=>'btn btn-primary'])}}
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{env("APP_URL")}}/resources/assets/js/libs/select2/select2.min.js"></script>
    @include('partial.form-error')
    <script>
        $("#head_delegate_id").select2();
    </script>
@endsection