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
                                    <td>{{$committee->abbreviation}}</td>
                                    <td>{{$committee->topic_chinese_name}}</td>
                                    <td>{{$committee->topic_english_name}}</td>
                                    <td>{{$committee->delegation}}</td>
                                    <td>{{$committee->number}}</td>
                                    <td><a href="{{url('committee',$committee->id)}}"><i
                                                    class="md md-mode-edit"></i></a></td>
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
    </script>
@endsection