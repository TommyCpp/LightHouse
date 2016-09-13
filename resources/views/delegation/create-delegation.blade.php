@extends('layout')


@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/select2/select2.css">

    @endsection
@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    创建代表团
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(['action'=>'DelegationController@create','class'=>'form'])}}
                    <div class="card">
                        <div class="card-body">
                            <div class='form-group'>
                                {{Form::label('name','代表团名称')}}
                                {{Form::text('name',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('delegation_number','代表团人数')}}
                                {{Form::text('delegation_number',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('head_delegate_id','代表团领队')}}
                                <select name="head_delegate_id" id="head_delegate_id">
                                    <optgroup label="代表">
                                        @foreach($delegates as $delegate)
                                            <option value="{{$delegate->id}}">{{$delegate->name}}
                                                ,{{$delegate->archive->HighSchool}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="主席">
                                        @foreach($daises as $dais)
                                            <option value="{{$dais->id}}">{{$dais->name}}
                                                ,{{$dais->archive->HighSchool}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            @foreach($committees as $committee)
                                <div class='form-group'>
                                    {{Form::label($committee->id,$committee->chinese_name." 会场席位数")}}
                                    {{Form::text($committee->id,null,['class'=>'form-control'])}}
                                </div>
                            @endforeach
                            <div class="form-group">
                                {{Form::submit('现在提交',['class'=>'btn btn-primary'])}}
                            </div>
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