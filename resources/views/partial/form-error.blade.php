@if (count($errors) > 0)
    <script>
        BootstrapDialog.show({
            title: "错误",
            message: "<div class=\"alert alert-danger\"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>",
            type: 'type-danger'
        })
    </script>
@endif