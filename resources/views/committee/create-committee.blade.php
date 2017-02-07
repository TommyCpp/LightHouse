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
                                {{Form::text('id',null,['class'=>'form-control'])}}
                                {{Form::label('id','会场编号')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('chinese_name',null,['class'=>'form-control'])}}
                                {{Form::label('chinese_name','中文名称')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('english_name',null,['class'=>'form-control'])}}
                                {{Form::label('english_name','英文名称')}}
                            </div>
                            <div class='form-group'>
                                {{Form::select('language',["chinese"=>"中文","english"=>"English"],null,['class'=>'form-control'])}}
                                {{Form::label('language','工作语言')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('abbreviation',null,['class'=>'form-control'])}}
                                {{Form::label('abbreviation','缩写')}}
                            </div>
                            <div class='form-group'>
                                {{Form::select('delegation',[1=>'单代表制',2=>'双代表制',0=>'混合代表制'],null,['class'=>'form-control'])}}
                                {{Form::label('delegation','代表制')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('number',null,['class'=>'form-control'])}}
                                {{Form::label('number','代表人数')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('topic_chinese_name',null,['class'=>'form-control'])}}
                                {{Form::label('topic_chinese_name','议题中文名称')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('topic_english_name',null,['class'=>'form-control'])}}
                                {{Form::label('topic_english_name','议题英文名称')}}
                            </div>
                            <div class='form-group'>
                                {{Form::textarea('note',null,['class'=>'form-control'])}}
                                {{Form::label('note','备注')}}
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