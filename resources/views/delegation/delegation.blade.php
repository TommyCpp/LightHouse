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
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                名额交换请求
                            </header>
                        </div>
                        <div class="card-body style-default-bright">
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
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($target_requests as $item)
                                        <tr>
                                            <td>{{$item['id']}}</td>
                                            <td>{{$item['initiator']}}</td>
                                            <td>{{$item['target']}}</td>
                                            @foreach($committees as $committee)
                                                <td>{{$item[$committee->abbreviation]}}</td>
                                            @endforeach
                                            @if($item['status'] == "success")
                                            <td><span class="tab label label-success">交换成功</span></td>
                                                @elseif($item['status'] == "fail")
                                                <td><span class="tab label label-danger">交换失败</span></td>
                                                @elseif($item['status'] == "padding")
                                                <td><span class="tab label label-primary">等待确认</span></td>
                                            @endif
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
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                发起的名额交换请求
                            </header>
                        </div>
                        <div class="card-body style-default-bright">
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
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($initiator_requests as $item)
                                    <tr>
                                        <td>{{$item['id']}}</td>
                                        <td>{{$item['initiator']}}</td>
                                        <td>{{$item['target']}}</td>
                                        @foreach($committees as $committee)
                                            <td>{{$item[$committee->abbreviation]}}</td>
                                        @endforeach
                                        @if($item['status'] == "success")
                                            <td><span class="tab label label-success">交换成功</span></td>
                                        @elseif($item['status'] == "fail")
                                            <td><span class="tab label label-danger">交换失败</span></td>
                                        @elseif($item['status'] == "padding")
                                            <td><span class="tab label label-primary">等待确认</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
            $.post("{{env('APP_URL')}}/public/committee/" + $committee_id + "/seats", {"_token": "{{csrf_token()}}"}, function (data) {
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
