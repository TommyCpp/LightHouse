@extends('layout')

@section('css')
    <link type="text/css" rel="stylesheet"
          href="{{asset("/assets/css/libs/DataTables/jquery.dataTables.css?1423553989")}}"/>
    <link type="text/css" rel="stylesheet"
          href="{{asset("/assets/css/libs/DataTables/extensions/dataTables.colVis.css?1423553990")}}"/>
    <link type="text/css" rel="stylesheet"
          href="{{asset("/assets/css/libs/DataTables/extensions/dataTables.tableTools.css?1423553990")}}"/>
    <link rel="stylesheet" href="{{asset("/assets/css/libs/toastr/toastr.css")}}"/>
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
                                @foreach($committee_names as $name)
                                    <th>{{$name}}</th>
                                @endforeach
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
                                    @foreach($committee_names as $name)
                                        <td>{{$seats[$delegation->id][$name]}}</td>
                                    @endforeach
                                    <td><a href="{{url("delegation/".$delegation->id."/edit")}}"><i
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
    <script src="{{asset("/assets/js/libs/DataTables/jquery.dataTables.min.js")}}"></script>
    <script src="{{asset("/assets/js/libs/DataTables/extensions/ColVis/js/dataTables.colVis.min.js")}}"></script>
    <script src="{{asset("/assets/js/libs/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js")}}"></script>
    <script src="{{asset("/assets/js/libs/toastr/toastr.js")}}"></script>
    <script>
        $('#delegations').DataTable({
            "language": {
                "url": '{{asset("/assets/js/libs/DataTables/i18n/chinese.json")}}'
            }
        });
        @if(session('error') != null)
            toastr.error('{{session('error')}}');
        @endif

        $("i.fa.fa-eye").click(function (e) {
            var committee_id = $(e.target).parent().data('target');
            var dialog = new BootstrapDialog({
                title: "备注",
                type: "type-primary"
            });
            $.get("committee/" + committee_id + "/note", function (data) {
                dialog.setMessage(data);
                dialog.open();
            });
        });


        $("i.fa.fa-trash").click(function (e) {
            var current_tr = $(e.target).parents("tr");
            var delegation_id = $(e.target).parent().data('target');
            BootstrapDialog.show({
                title: "确认",
                type: "type-warning",
                message: "确认删除" + delegation_id + "号代表团",
                buttons: [{
                    id: "btn-ok",
                    label: "确定",
                    cssClass: "btn btn-danger",
                    action: function (dialog) {
                        $.ajax({
                            url: "delegation/" + delegation_id,
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
        })
    </script>
@endsection