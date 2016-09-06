<!-- BEGIN MENUBAR-->
<div id="menubar" class="menubar-inverse ">
    <div class="menubar-fixed-panel">
        <div>
            <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="expanded">
            <a href="/">
                <span class="text-lg text-bold text-primary ">test</span>
            </a>
        </div>
    </div>
    <div class="menubar-scroll-panel">

        <!-- BEGIN MAIN MENU -->
        <ul id="main-menu" class="gui-controls">

            <!-- BEGIN DASHBOARD -->
            @foreach($menus as $item)
                <li>
                    <a href="{{array_key_exists('url',$item) ? url($item['url']) : $item['link']}}">
                        <div class="gui-icon">
                            @if(array_key_exists('icon',$item))
                                <i class="md {{$item['icon']}}"></i>
                            @else
                                <i class="md md-home"></i>
                            @endif
                        </div>
                        <span class="title">{{$item['name']}}</span>
                    </a>
                    @if(array_key_exists('offspring',$item))
                        <ul>
                            @foreach($item['offspring'] as $sub)
                                <li>
                                    <a href="{{array_key_exists('url',$sub)?url($sub['url']):$sub['link']}}">
                                        <span class="title">{{$sub['name']}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
                @endforeach

                        <!-- END DASHBOARD -->
        </ul><!--end .main-menu -->
        <!-- END MAIN MENU -->

        <div class="menubar-foot-panel">
            <small class="no-linebreak hidden-folded">
                <span class="opacity-75">Copyright &copy; 2014</span> <strong>CodeCovers</strong>
            </small>
        </div>
    </div><!--end .menubar-scroll-panel-->
</div><!--end #menubar-->
<!-- END MENUBAR -->