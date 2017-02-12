@extends('layout')

@section('css')
    <link type="text/css" rel="stylesheet"
          href="{{env('APP_URL')}}/resources/assets/css/libs/DataTables/jquery.dataTables.css?1423553989"/>
    <link type="text/css" rel="stylesheet"
          href="{{env('APP_URL')}}/resources/assets/css/libs/DataTables/extensions/dataTables.colVis.css?1423553990"/>
    <link type="text/css" rel="stylesheet"
          href="{{env('APP_URL')}}/resources/assets/css/libs/DataTables/extensions/dataTables.tableTools.css?1423553990"/>
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/toastr/toastr.css"/>
@endsection

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    用户列表
                </li>
            </ol>
        </div>
        <div class="section-body">
            <div class="row style-default-bright">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="users" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>姓名</th>
                                <th>姓拼音</th>
                                <th>名拼音</th>
                                <th>身份</th>
                                <th>高中</th>
                                <th>大学</th>
                                <th>编辑</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->archive->LastName}}</td>
                                    <td>{{$user->archive->FirstName}}</td>
                                    <td>@if($user->identities() !== false)
                                            @foreach($user->identities() as $identity)
                                                <span class="tab label label-info">{{$identity}}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{$user->archive->HighSchool}}</td>
                                    <td>{{$user->archive->University}}</td>
                                    <td><a href="{{url('user',$user->id)}}"><i
                                                    class="md md-mode-edit"></i></a>
                                        <a href="javascript:void(0);" data-target="{{$user->id}}"><i
                                                    class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/DataTables/jquery.dataTables.min.js"></script>
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/DataTables/extensions/ColVis/js/dataTables.colVis.min.js"></script>
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="{{env('APP_URL')}}/resources/assets/js/libs/toastr/toastr.js"></script>
    <script>
        var table = $('#users').DataTable({
                "language":{
                "url":'{{env('APP_URL')}}/resources/assets/js/libs/DataTables/i18n/chinese.json'
            }
        });
        @if(session('error') != null)
            toastr.error('{{session('error')}}');
        @endif
        $("#users").on("click", ".fa-trash", function (e) {
            var current_tr = $(e.target).parents("tr");
            var user_id = $(e.target).parent().data('target');
            BootstrapDialog.show({
                title: "确认",
                type: "type-warning",
                message: "确认删除" + user_id + "用户",
                buttons: [{
                    id: "btn-ok",
                    label: "确定",
                    cssClass: "btn btn-danger",
                    action: function (dialog) {
                        $.ajax({
                            url: "user/" + user_id,
                            method: "POST",
                            data: {
                                _method: "DELETE",
                                _token: "{{csrf_token()}}"
                            },
                            success: function (data) {
                                $(current_tr).remove();
                                dialog.close();
                            }
                        })
                    }
                }, {
                    id: "btn-close",
                    label: "取消",
                    cssClass: "btn btn-primary",
                    action: function (dialog) {
                        dialog.close();
                    }
                }
                ]
            })
        });
    </script>
@endsection