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
                                {{Form::text('id',$user->id,['readonly','class'=>'form-control'])}}
                                {{Form::label('id','ID')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('email',Request::user()->email,['class'=>'form-control','readonly'])}}
                                {{Form::label('email','Email')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('name',Request::user()->name,['class'=>'form-control'])}}
                                {{Form::label('name','姓名')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('first-name',$user->FirstName,['class'=>'form-control'])}}
                                {{Form::label('first-name','名拼音')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('last-name',$user->LastName,['class'=>'form-control'])}}
                                {{Form::label('last-name','姓拼音')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('high-school',$user->HighSchool,['class'=>'form-control'])}}
                                {{Form::label('high-school','高中学校名称')}}
                            </div>
                            <div class='form-group'>
                                {{Form::text('university',$user->University,['class'=>'form-control'])}}
                                {{Form::label('university','大学名称')}}
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

@section('js')
    @include('partial.form-error')
@endsection