@extends('layout')

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    用户详细信息
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">

                    {{ Form::open(['action'=>['UserArchiveController@addOrUpdate'],'class'=>'form'])}}
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                {{Form::label('id','ID')}}
                                {{Form::text('id',$user->id,['readonly','class'=>'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{Form::label('first-name','姓拼音')}}
                                {{Form::text('first-name',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('last-name','名拼音')}}
                                {{Form::text('last-name',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('high-school','高中学校名称')}}
                                {{Form::text('high-school',null,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('university','大学名称')}}
                                {{Form::text('university',null,['class'=>'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{Form::submit('现在提交',['class'=>'btn btn-flat btn-primary'])}}
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection