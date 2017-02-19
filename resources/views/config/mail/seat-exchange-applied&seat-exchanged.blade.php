@extends('layout')

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    修改配置
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{ Form::open(['url'=>Request::url(),'class'=>'form'])}}
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                {{Form::text('sender',$configs['sender'],['class'=>'form-control'])}}
                                {{Form::label('sender','发件地址')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('ccs',implode(",",$configs['ccs']),['class'=>'form-control'])}}
                                {{Form::label('ccs','抄送')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('initiator_subject',$configs['initiator_subject'],['class'=>'form-control'])}}
                                {{Form::label('initiator_subject','名额交换发起者 - 邮件标题')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('target_subject',$configs['target_subject'],['class'=>'form-control'])}}
                                {{Form::label('target_subject','名额交换目标 - 邮件标题')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('emergence_contact',$configs['emergence_contact'],['class'=>'form-control'])}}
                                {{Form::label('emergence_contact','紧急联系地址')}}
                            </div>
                            <div class="form-group">
                                {{Form::submit('现在提交',['class'=>'btn btn-primary'])}}
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @include('partial.form-error')
@endsection