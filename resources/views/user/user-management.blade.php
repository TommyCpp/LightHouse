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
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->archive->LastName}}</td>
                                    <td>{{$user->archive->FirstName}}</td>
                                    <td><span class="tag label label-info">{{$user->archive->Identity}}</span></td>
                                    <td>{{$user->archive->HighSchool}}</td>
                                    <td>{{$user->archive->University}}</td>
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
        $('#users').DataTable();
    </script>
@endsection