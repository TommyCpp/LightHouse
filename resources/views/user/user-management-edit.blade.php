@extends('layout')

@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/multi-select/multi-select.css"/>
@endsection
@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    编辑用户{{$user->id}}的信息
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(['action'=>['UserController@editUserInformation',$user->id],'class'=>'form'])}}
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            {{Form::label('id','ID')}}
                            {{Form::text('id',$user->id,['class'=>'form-control','readonly'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('name','姓名')}}
                            {{Form::text('name',$user->name,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('first-name','名拼音')}}
                            {{Form::text('first-name',$user->archive->FirstName,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('last-name','姓拼音')}}
                            {{Form::text('last-name',$user->archive->LastName,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('high-school','高中学校名称')}}
                            {{Form::text('high-school',$user->archive->HighSchool,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('university','大学名称')}}
                            {{Form::text('university',$user->archive->University,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('identity','身份')}}
                            {{Form::select('identity',[
                            "ADMIN"=>"管理员",
                            "DAIS"=>'主席',
                            "OT"=>'会务运营团队',
                            "AT"=>'学术管理团队',
                            "DIR"=>"理事",
                            "COREDIR"=>"核心理事",
                            "VOL"=>"志愿者",
                            "DEL"=>"代表",
                            "HEADDEL"=>"代表团领队",
                            "OTHER"=>"其他"
                            ],explode(",",$user->archive->Identity),['class'=>'form-control','multiple'])}}
                        </div>
                        <div class="form-group">
                            {{Form::submit('现在提交',['class'=>'btn btn-flat btn-primary'])}}
                        </div>

                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/multi-select/jquery.multi-select.js"></script>
    <script>
        $('#identity').multiSelect();
    </script>
@endsection