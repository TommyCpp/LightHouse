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
                    会场列表
                </li>
            </ol>
        </div>
        <div class="section-body">
            <div class="row style-default-bright">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="committees">
                            <thead>
                            <tr>
                                <th>会场编号</th>
                                <th>会场中文名称</th>
                                <th>会场英文名称</th>
                                <th>工作语言</th>
                                <th>会场缩写</th>
                                <th>议题中文名称</th>
                                <th>议题英文名称</th>
                                <th>代表制</th>
                                <th>代表人数</th>
                                <th>编辑</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($committees as $committee)
                                <tr>
                                    <td>{{$committee->id}}</td>
                                    <td>{{$committee->chinese_name}}</td>
                                    <td>{{$committee->english_name}}</td>
                                    <td>{{$committee->format_language}}</td>
                                    <td>{{$committee->abbreviation}}</td>
                                    <td>{{$committee->topic_chinese_name}}</td>
                                    <td>{{$committee->topic_english_name}}</td>
                                    <td>{{$committee->delegation}}</td>
                                    <td>{{$committee->number}}</td>
                                    <td><a href="javascript:void(0)" data-target="{{$committee->id}}"><i
                                                    class="fa fa-eye"></i></a>
                                        <a href="{{url("committee/".$committee->id."/edit")}}"><i
                                                    class="md md-mode-edit"></i></a>
                                        <a href="javascript:void(0);" data-target="{{$committee->id}}"><i
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
        $('#committees').DataTable({
            "language": {
                "lengthMenu": "每页显示 _MENU_ 条",
                "zeroRecords": "没有任何记录",
                "info": "显示 _PAGES_ 中的 _PAGE_ 页",
                "infoEmpty": "没有数据",
                "infoFiltered": "(从 _MAX_ 个数据中筛选)",
                "search": "搜索"
            }
        });
        @if(session('error') != null)
            toastr.error('{{session('error')}}');
        @endif

        $("i.fa.fa-eye").click(function(e){
            var committee_id = $(e.target).parent().data('target');
            var dialog = new BootstrapDialog({
                title:"备注",
                type:"type-primary"
            });
            $.get("committee/"+committee_id+"/note",function(data){
                dialog.setMessage(data);
                dialog.open();
            });
        });


        $("i.fa.fa-trash").click(function (e) {
            var current_tr = $(e.target).parents("tr");
            var committee_id = $(e.target).parent().data('target');
            BootstrapDialog.show({
                title: "确认",
                type: "type-warning",
                message: "确认删除" + committee_id + "号会场",
                buttons: [{
                    id: "btn-ok",
                    label: "确定",
                    cssClass: "btn btn-danger",
                    action: function (dialog) {
                        $.ajax({
                            url:"committee/"+ committee_id,
                            method:"POST",
                            data:{
                                _method:"DELETE",
                                _token:"{{csrf_token()}}"
                            },
                            success:function(data){
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
        })
    </script>
@endsection