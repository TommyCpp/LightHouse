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
                   创建用户
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(['action'=>['UserController@create'],'class'=>'form','id'=>'create-form'])}}
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            {{Form::label('name','姓名')}}
                            {{Form::text('name',null,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('first-name','名拼音')}}
                            {{Form::text('first-name',null,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('last-name','姓拼音')}}
                            {{Form::text('last-name',null,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('high-school','高中学校名称')}}
                            {{Form::text('high-school',null,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('university','大学名称')}}
                            {{Form::text('university',null,['class'=>'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('identity','身份')}}
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
                            ],null,['class'=>'form-control','multiple'=>true,'id'=>'identities'])}}
                        </div>
                        <div class="form-group">
                            {{Form::button('现在提交',['class'=>'btn btn-flat btn-primary','id'=>'submit'])}}
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
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/bootstrap-dialog/bootstrap-dialog.js"></script>
    <script>
        $('#identities').multiSelect();
        $("#submit").click(function () {
            $.ajax({
                url: location.href,
                type: "POST",
                dataType: 'json',
                data: $('#create-form').serializeArray(),
                success: function () {
                    BootstrapDialog.success('修改成功');
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