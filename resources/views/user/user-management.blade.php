@extends('layout')

@section('css')
    <link type="text/css" rel="stylesheet"
          href="http://localhost/LightHouse/resources/assets/css/libs/DataTables/jquery.dataTables.css?1423553989"/>
    <link type="text/css" rel="stylesheet"
          href="http://localhost/LightHouse/resources/assets/css/libs/DataTables/extensions/dataTables.colVis.css?1423553990"/>
    <link type="text/css" rel="stylesheet"
          href="http://localhost/LightHouse/resources/assets/css/libs/DataTables/extensions/dataTables.tableTools.css?1423553990"/>
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
                                    <td><a href="{{url('user-management',$user->id)}}"><i class="md md-mode-edit"></i></a></td>
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
    <script src="http://localhost/LightHouse/resources/assets/js/libs/DataTables/jquery.dataTables.min.js"></script>
    <script src="http://localhost/LightHouse/resources/assets/js/libs/DataTables/extensions/ColVis/js/dataTables.colVis.min.js"></script>
    <script src="http://localhost/LightHouse/resources/assets/js/libs/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
    <script>
        $('#users').DataTable({
            "language": {
                "lengthMenu": "每页显示 _MENU_ 条",
                "zeroRecords": "没有任何记录",
                "info": "显示 _PAGES_ 中的 _PAGE_ 页",
                "infoEmpty": "没有数据",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search":"搜索"
            }
        });
    </script>
@endsection