@extends('layout')

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    代表团信息
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                代表团名称
                            </header>
                        </div>
                        <div class="card-body style-default-bright">
                            <ul class="list">
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">{{$delegation->name}}</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                代表团领队名称
                            </header>
                        </div>
                        <div class="card-body style-default-bright">
                            <ul class="list">
                                <li class="tile">
                                    <div class="tile-content">
                                        <div class="tile-text">{{$delegation->head_delegate->name}}</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card style-primary">
                        <div class="card-head">
                            <header>
                                代表团名额
                            </header>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover no-margin">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>会场名称</th>
                                    <th>席位数</th>
                                    <th>席位上限</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($committees as $committee)
                                    <tr>
                                        <td>{{$committee->id}}</td>
                                        <td>{{$committee->abbreviation}}</td>
                                        <td>{{$seats[$committee->abbreviation]}}</td>
                                        <td>{{$committee->limit}}</td>
                                        <td><a href="javascript:void(0)" data-target="{{$committee->id}}"><i
                                                        class="fa fa-eye"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-bordered style-primary" id="received-seat-exchange-request">
                        <div class="card-head">
                            <div class="tools">
                                <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-down"></i></a>
                            </div>
                            <ul class="nav nav-tabs pull-right tabs-text-contrast tabs-primary-light"
                                data-toggle="tabs">
                                <li class="active">
                                    <a href="#target-all">全部</a>
                                </li>
                                <li><a href="#target-fail">失败</a></li>
                                <li><a href="#target-success">成功</a></li>
                                <li><a href="#target-pending">待处理</a></li>
                            </ul>
                            <header>
                                收到的名额交换请求
                            </header>
                        </div>
                        <div class="card-body style-default-bright tab-content">
                            <div class="tab-pane active" id="target-all">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($target_requests as $item)
                                        <tr data-target="{{$item["id"]}}">
                                            <td>{{$item['id']}}</td>
                                            <td>{{$item['initiator']}}</td>
                                            <td>{{$item['target']}}</td>
                                            @foreach($committees as $committee)
                                                <td>{{$item[$committee->abbreviation]}}</td>
                                            @endforeach
                                            <td class="exchange-status">
                                                @if($item['status'] == "success")
                                                    <span class="tab label label-success">交换成功</span>
                                                @elseif($item['status'] == "fail")
                                                    <span class="tab label label-danger">交换失败</span>
                                                @elseif($item['status'] == "pending")
                                                    <span class="tab label label-primary">等待确认</span>
                                                @endif
                                            </td>
                                            @if($item['status'] == "pending")
                                                <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                class="fa fa-ban"></i></a></td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="target-fail">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr data-target="{{$item["id"]}}">
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($target_requests as $item)
                                        @if($item['status']=="fail")
                                        <tr data-target="{{$item["id"]}}">
                                            <td>{{$item['id']}}</td>
                                            <td>{{$item['initiator']}}</td>
                                            <td>{{$item['target']}}</td>
                                            @foreach($committees as $committee)
                                                <td>{{$item[$committee->abbreviation]}}</td>
                                            @endforeach
                                            <td class="exchange-status">
                                                    <span class="tab label label-danger">交换失败</span>
                                            </td>
                                            @if($item['status'] == "pending")
                                                <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                class="fa fa-ban"></i></a></td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="target-success">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($target_requests as $item)
                                        @if($item['status']=="success")

                                            <tr data-target="{{$item["id"]}}">
                                                <td>{{$item['id']}}</td>
                                                <td>{{$item['initiator']}}</td>
                                                <td>{{$item['target']}}</td>
                                                @foreach($committees as $committee)
                                                    <td>{{$item[$committee->abbreviation]}}</td>
                                                @endforeach
                                                <td class="exchange-status">
                                                    <span class="tab label label-success">交换成功</span>
                                                </td>
                                                @if($item['status'] == "pending")
                                                    <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                    class="fa fa-ban"></i></a></td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="target-pending">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($target_requests as $item)
                                        @if($item['status']=="pending")

                                            <tr>
                                                <td>{{$item['id']}}</td>
                                                <td>{{$item['initiator']}}</td>
                                                <td>{{$item['target']}}</td>
                                                @foreach($committees as $committee)
                                                    <td>{{$item[$committee->abbreviation]}}</td>
                                                @endforeach
                                                <td class="exchange-status">
                                                    <span class="tab label label-primary">等待确认</span>
                                                </td>
                                                @if($item['status'] == "pending")
                                                    <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                    class="fa fa-ban"></i></a></td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-bordered style-primary" id="seat-exchange-request">
                        <div class="card-head">
                            <div class="tools">
                                <a class="btn btn-icon-toggle btn-collapse"><i class="fa fa-angle-down"></i></a>
                            </div>
                            <ul class="nav nav-tabs pull-right tabs-text-contrast tabs-primary-light"
                                data-toggle="tabs">
                                <li class="active">
                                    <a href="#initiator-all">全部</a>
                                </li>
                                <li><a href="#initiator-fail">失败</a></li>
                                <li><a href="#initiator-success">成功</a></li>
                                <li><a href="#initiator-pending">待处理</a></li>
                            </ul>
                            <header>
                                发起的名额交换请求
                            </header>
                        </div>
                        <div class="card-body style-default-bright tab-content">
                            <div class="tab-pane active" id="initiator-all">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($initiator_requests as $item)
                                        <tr data-target="{{$item["id"]}}">
                                            <td>{{$item['id']}}</td>
                                            <td>{{$item['initiator']}}</td>
                                            <td>{{$item['target']}}</td>
                                            @foreach($committees as $committee)
                                                <td>{{$item[$committee->abbreviation]}}</td>
                                            @endforeach
                                            <td class="exchange-status">
                                                @if($item['status'] == "success")
                                                    <span class="tab label label-success">交换成功</span>
                                                @elseif($item['status'] == "fail")
                                                    <span class="tab label label-danger">交换失败</span>
                                                @elseif($item['status'] == "pending")
                                                    <span class="tab label label-primary">等待确认</span>
                                                @endif
                                            </td>
                                            @if($item['status'] == "pending")
                                                <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                class="fa fa-ban"></i></a></td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="initiator-fail">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($initiator_requests as $item)
                                        @if($item['status']=="fail")
                                        <tr data-target="{{$item["id"]}}">
                                            <td>{{$item['id']}}</td>
                                            <td>{{$item['initiator']}}</td>
                                            <td>{{$item['target']}}</td>
                                            @foreach($committees as $committee)
                                                <td>{{$item[$committee->abbreviation]}}</td>
                                            @endforeach
                                            <td class="exchange-status">
                                                    <span class="tab label label-danger">交换失败</span>
                                            </td>
                                            @if($item['status'] == "pending")
                                                <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                class="fa fa-ban"></i></a></td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="initiator-pending">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($initiator_requests as $item)
                                        @if($item['status']=="pending")
                                            <tr data-target="{{$item["id"]}}">
                                                <td>{{$item['id']}}</td>
                                                <td>{{$item['initiator']}}</td>
                                                <td>{{$item['target']}}</td>
                                                @foreach($committees as $committee)
                                                    <td>{{$item[$committee->abbreviation]}}</td>
                                                @endforeach
                                                <td class="exchange-status">
                                                    <span class="tab label label-primary">等待确认</span>
                                                </td>
                                                @if($item['status'] == "pending")
                                                    <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                    class="fa fa-ban"></i></a></td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="initiator-success">
                                <table class="table table-hover no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>发起方</th>
                                        <th>目标方</th>
                                        @foreach($committees as $committee)
                                            <th>{{$committee->abbreviation}}</th>
                                        @endforeach
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($initiator_requests as $item)
                                        @if($item['status']=="success")
                                            <tr data-target="{{$item["id"]}}">
                                                <td>{{$item['id']}}</td>
                                                <td>{{$item['initiator']}}</td>
                                                <td>{{$item['target']}}</td>
                                                @foreach($committees as $committee)
                                                    <td>{{$item[$committee->abbreviation]}}</td>
                                                @endforeach
                                                <td class="exchange-status">
                                                    <span class="tab label label-success">交换成功</span>
                                                </td>
                                                @if($item['status'] == "pending")
                                                    <td><a href="javascript:void(0)" data-target="{{$item['id']}}"><i
                                                                    class="fa fa-ban"></i></a></td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @include('partial/form-error')
    <script>
        $('.card .btn.btn-icon-toggle.btn-collapse').click(function (e) {
            $(this).parents(".card").toggleClass("card-collapsed");
            $(this).parents(".card-head").siblings(".card-body").slideToggle();
        });
        $('a:has(".fa-ban")').click(function () {
            var exchange = this;
            $.post("{{url('delegation-seat-exchange')}}/" + $(this).data('target'), {
                "_method": "DELETE",
                "delegation-id": "{{$delegation->id}}",
                "_token": "{{csrf_token()}}"
            }, function (data) {
                if (data == "success") {
                    BootstrapDialog.show({
                        type: "type-success",
                        title: "成功",
                        message: "已经删除此交换申请",
                        onhidden:function(){
                            location.reload();
                        }
                    });
//                    $(exchange).parents('td').siblings('.exchange-status').find('span').removeClass('label-primary').addClass('label-danger').html("交换失败");
//                    $(exchange).parents('td').html("");
//                    //Update Tabs
//                    $tr = $(exchange).parents("tr");

                }
                else {
                    BootstrapDialog.show({
                        type: "type-warning",
                        title: "失败",
                        message: "此申请未能成功删除"
                    })
                }
            });
        });
        $('table tr td a i.fa.fa-eye').click(function (e) {
            $dialog = new BootstrapDialog();
            $dialog.setType("type-info");
            $dialog.setSize("size-wide");
            $dialog.setTitle("会场名额分布");
            $dialog.setId("seats-information");
            $dialog.open();
            $dialog.setMessage("载入中");
            getSeatsData($(e.target).parent().data('target'));
        });

        function getSeatsData($committee_id) {
            $.post("{{url("committee")}}/" + $committee_id + "/seats", {"_token": "{{csrf_token()}}"}, function (data) {
//                console.log(data);
                $("#seats-information .bootstrap-dialog-message").html("");
                $('<table class="table table-responsive" id="seat-table"><thead><tr><th>代表团编号</tf><th>代表团名称</th><th>席位数</th></tr></thead><tbody></tbody></table>').appendTo($("#seats-information .bootstrap-dialog-message"));
                for (var i = 0; i < data.length; i++) {
                    if (data[i][0] != "{{$delegation->id}}") {
                        $("#seat-table tbody").append($("<tr><td>" + data[i][0] + "</td><td>" + data[i][1] + "</td><td>" + data[i][2] + "</td></tr>"));
                    }
                }
            });

        }
    </script>
@endsection
