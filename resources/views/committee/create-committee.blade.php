@extends('layout')

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    创建会场
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(['url'=>'create-committee','class'=>'form'])}}
                    <div class="card">
                        <div class="card-body">
                            <div class='form-group'>
                                {{Form::label('id','会场编号')}}
                                {{Form::text('id',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('chinese_name','中文名称')}}
                                {{Form::text('chinese_name',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('english_name','英文名称')}}
                                {{Form::text('english_name',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('abbreviation','缩写')}}
                                {{Form::text('abbreviation',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('delegation','代表制')}}
                                {{Form::select('delegation',[1=>'单代表制',2=>'双代表制',0=>'混合代表制'],null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('number','代表人数')}}
                                {{Form::text('number',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('topic_chinese_name','议题中文名称')}}
                                {{Form::text('topic_chinese_name',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('topic_english_name','议题英文名称')}}
                                {{Form::text('topic_english_name',null,['class'=>'form-control'])}}
                            </div>
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
    @include('partial.form-error')
@endsection