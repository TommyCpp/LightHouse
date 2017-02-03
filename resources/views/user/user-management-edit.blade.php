@extends('layout')

@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/multi-select/multi-select.css"/>
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/bootstrap-dialog/bootstrap-dialog.css"/>
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
                    {{Form::open(['action'=>['UserController@editUserInformation',$user->id],'class'=>'form','id'=>'edit-form'])}}

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                {{Form::text('id',$user->id,['class'=>'form-control','readonly'])}}
                                {{Form::label('id','ID')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('name',$user->name,['class'=>'form-control'])}}
                                {{Form::label('name','姓名')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('first-name',$user->archive->FirstName,['class'=>'form-control'])}}
                                {{Form::label('first-name','名拼音')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('last-name',$user->archive->LastName,['class'=>'form-control'])}}
                                {{Form::label('last-name','姓拼音')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('high-school',$user->archive->HighSchool,['class'=>'form-control'])}}
                                {{Form::label('high-school','高中学校名称')}}
                            </div>
                            <div class="form-group">
                                {{Form::text('university',$user->archive->University,['class'=>'form-control'])}}
                                {{Form::label('university','大学名称')}}
                            </div>
                            <div class="form-group" style="padding-top:24px;">
                                {{Form::select('identity[]',[
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
                                ],explode(",",$user->archive->Identity),['class'=>'form-control','multiple'=>true,'id'=>'identities'])}}
                                {{Form::label('identity','身份')}}
                            </div>
                            <div class="form-group">
                                {{Form::button('现在提交',['class'=>'btn btn-flat btn-primary','id'=>'submit'])}}
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
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/multi-select/jquery.multi-select.js"></script>
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/bootstrap-dialog/bootstrap-dialog.js"></script>
    <script>
        $('#identities').multiSelect();
        $("#submit").click(function () {
            $.ajax({
                url: location.href,
                type: "POST",
                dataType: 'json',
                data: $('#edit-form').serializeArray(),
                success: function () {
                    BootstrapDialog.show({
                        title: "修改成功",
                        type: "type-success",
                        message: "用户信息修改成功",
                        buttons: [{
                            id: "redirect",
                            label: "返回用户列表",
                            cssClass: "btn btn-success",
                            action: function (dialog) {
                                window.location.href = "{{env("APP_URL")}}" + "/public/users";
                            }
                        },
                            {
                                id: "close",
                                label: "关闭",
                                cssClass: "btn btn-danger",
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    });

                },
                error: function (data) {
                    var errors = data.responseJSON;
                    var errors_string = "";
                    $(".form-group").removeClass('has-error');
                    for (error in errors) {
                        errors_string += "<li>" + errors[error] + "</li>";
                        $("#" + error).parent(".form-group").addClass('has-error');
                    }
                    BootstrapDialog.show({
                        title: '错误',
                        message: errors_string,
                        type: 'type-danger'
                    })
                }
            })
        })
    </script>
@endsection