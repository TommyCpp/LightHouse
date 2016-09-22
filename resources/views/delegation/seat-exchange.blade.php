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
                                    @foreach($delegations as $delegation)
                                        @if($delegation->id != Auth::user()->delegation->id)
                                            <option value="{{$delegation->id}}">{{$delegation->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
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
                                               id="{{$committee->abbreviation}}-in" class="form-control"/>
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
                                               id="{{$committee->abbreviation}}-out" class="form-control"/>
                                    </div>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{env("APP_URL")}}/resources/assets/js/libs/select2/select2.min.js"></script>
    @include('partial/form-error')
    <script>
        $('#target').select2();
    </script>
@endsection