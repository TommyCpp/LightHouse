@extends('layout')

@section('css')
    <link rel="stylesheet" href="{{env('APP_URL')}}/resources/assets/css/libs/select2/select2.css">
@endsection

@section('content')
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">
                    修改代表团信息
                </li>
            </ol>
        </div>
        <div class="section-body contain-lg">
            <div class="row">
                <div class="col-lg-12">
                    {{Form::open(["url"=>"delegation/".$delegation->id,'class'=>"form",'method'=>"PUT"])}}
                    <div class="card">
                        <div class="card-body">
                            <div class='form-group'>
                                {{Form::label('id','代表团ID')}}
                                {{Form::text('id',$delegation->id,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('name','代表团名称')}}
                                {{Form::text('name',$delegation->name,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('delegate_number','代表团人数')}}
                                {{Form::text('delegate_number',$delegation->delegate_number,['class'=>'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('head_delegate_id','代表团领队')}}
                                <select name="head_delegate_id" id="head_delegate_id">
                                    @foreach($delegates as $delegate)
                                        @if($delegate->id == $delegation->head_delegate->id)
                                            <option value="{{$delegate->id}}" selected>{{$delegate->name}}
                                                ,{{$delegate->archive->HighSchool}}</option>
                                        @else
                                            <option value="{{$delegate->id}}">{{$delegate->name}}
                                                ,{{$delegate->archive->HighSchool}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @foreach($committee_seats as $committee_seat)
                                <div class='form-group'>
                                    {{Form::label($committee_seat['committee'],$committee_seat['committee']." 会场席位数")}}
                                    {{Form::text($committee_seat['committee'],$committee_seat['seats'],['class'=>'form-control'])}}
                                </div>
                            @endforeach
                            <div class="form-group">
                                {{Form::submit('现在提交',['class'=>'btn btn-primary'])}}
                            </div>
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
        $("#head_delegate_id").select2();
    </script>
@endsection
