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
                    代表团列表
                </li>
            </ol>
        </div>
        <div class="section-body">
            <div class="row style-default-bright">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="delegations">
                            <thead>
                            <tr>
                                <th>代表团编号</th>
                                <th>代表团名称</th>
                                <th>代表团领队名称</th>
                                <th>代表团人数</th>
                                <th>代表团席位总数</th>
                                <th>编辑</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($delegations as $delegation)
                                <tr>
                                    <td>{{$delegation->id}}</td>
                                    <td>{{$delegation->name}}</td>
                                    <td>{{$delegation->head_delegation_name}}</td>
                                    <td>{{$delegation->delegate_number}}</td>
                                    <td>{{$delegation->seat_number}}</td>
                                    <td><a href="javascript:void(0)" data-target="{{$delegation->id}}"><i
                                                    class="fa fa-eye"></i></a>
                                        <a href="{{url("committee/".$committee->id."/edit")}}"><i
                                                    class="md md-mode-edit"></i></a>
                                        <a href="javascript:void(0);" data-target="{{$delegation->id}}"><i
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
        $('#delegations').DataTable({
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