@extends('layout')

@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/select2/select2.css">
@endsection

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    代表团名额交换
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                目标代表团
                            </header>
                        </div>
                        <div class="card-body  style-default-bright">
                            <form action="javascript:void(0)" id="target-delegation">
                                <select name="target" id="target">
                                    <option></option>
                                    @foreach($delegations as $delegation)
                                        @if($delegation->id != Auth::user()->delegation->id)
                                            <option value="{{$delegation->id}}">{{$delegation->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                            <table class="table"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                获取席位
                            </header>
                        </div>
                        <div class="card-body style-default-bright">
                            <form action="javascript:void(0)" id="seats-in">
                                @foreach($committees as $committee)
                                    <div class="form-group">
                                        <label for="{{$committee->abbreviation}}-in">{{$committee->chinese_name}}</label>
                                        <input type="text" name="{{$committee->abbreviation}}-in"
                                               id="{{$committee->abbreviation}}-in" class="form-control" value="0"/>
                                    </div>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-bordered style-primary">
                        <div class="card-head">
                            <header>
                                送出席位
                            </header>
                        </div>
                        <div class="card-body style-default-bright">
                            <form action="javascript:void(0)" id="seats-out">
                                @foreach($committees as $committee)
                                    <div class="form-group">
                                        <label for="{{$committee->abbreviation}}-out">{{$committee->chinese_name}}</label>
                                        <input type="text" name="{{$committee->abbreviation}}-out"
                                               id="{{$committee->abbreviation}}-out" class="form-control" value="0"/>
                                    </div>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn ink-reaction btn-raised btn-primary btn-block" id="submit">提交申请</button>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{env("APP_URL")}}/resources/assets/js/libs/select2/select2.min.js"></script>
    <script>
        $('#target').select2({
            placeholder: "请选择目标代表团"
        });
        $(document).ready(function () {
            $("#submit").click(function (e) {
                var $button = e.target;
//                $($button).prop("disabled", true);
                //防止连续点击两次

                $inData = $('#seats-in').serializeArray();
                $outData = $('#seats-out').serializeArray();

                $data = $inData.concat($outData).concat($('#target').serializeArray()).concat({
                    "name": "_token",
                    "value": "{{csrf_token()}}"
                });

                $.ajax({
                    url: "{{env('APP_URL')}}/public/delegation-seat-exchange",
                    method: "POST",
                    data: $data,
                    statusCode: {
                        400: function (response) {
                            data = response.responseJSON;
                            var lis = "";
                            for (var i = 0; i < data.length; i++) {
                                lis += ("<li>" + data[i] + "</li>");
                            }
                            BootstrapDialog.show({
                                title: "错误",
                                message: "<div class=\"alert alert-danger\"><ul>" + lis + "</ul></div>",
                                type: 'type-danger'
                            })
                        },
                        422: function () {
                            BootstrapDialog.show({
                                title: "错误",
                                message: "<div class=\"alert alert-danger\">表单中存在未填写项、负数、或者交换数量超过该会场限额</div>",
                                type: 'type-danger'
                            })
                        }

                    }
                })

            })
        })
    </script>
@endsection