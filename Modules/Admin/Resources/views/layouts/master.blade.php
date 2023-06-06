@extends('admin::layouts.base')

@section('body-class')
@parent
page-md page-header-fixed page-sidebar-closed-hide-logo
@stop

@section('global-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/uniform/css/uniform.default.min.css') ) !!}
@stop

@section('page-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/select2/select2.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') ) !!}
@stop

@section('template-level-styles')
@parent
{!! HTML::style( URL::asset('global/css/components-md.css') ) !!}
{!! HTML::style( URL::asset('global/css/plugins-md.css') ) !!}
{!! HTML::style( URL::asset('admintheme/layout4/css/layout.css') ) !!}
{!! HTML::style( URL::asset('admintheme/layout4/css/themes/light.css') ) !!}
@stop

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('global/scripts/metronic.js') ) !!}
{!! HTML::script( URL::asset('admintheme/layout4/scripts/layout.js') ) !!}
{!! HTML::script( URL::asset('global/scripts/datatable.js') ) !!}
{!! HTML::script( URL::asset('js/admin/common.js') ) !!}
{!! HTML::script( URL::asset('js/validation.js') ) !!}
@stop


@section('page-level-scripts')
@parent
{!! HTML::script( URL::asset('global/plugins/bootbox/bootbox.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-validation/js/jquery.validate.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-validation/js/additional-methods.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/select2/select2.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/datatables/media/js/jquery.dataTables.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/datatables/media/js/dataTables.bootstrap.js') ) !!}
@stop

@section('scripts')
@parent
<script>
    jQuery(document).ready(function () {
        Metronic.setAssetsPath("{!! URL::asset('') !!}");
        Metronic.init();
        Layout.init();
        siteObjJs.admin.commonJs.expandCollapseMenu();
        siteObjJs.admin.commonJs.showSelectedMenus();
    });
</script>
@stop

@php $userinfo = \Modules\Admin\Services\Helper\UserInfoHelper::getAuthUserInfo();

$headerDescription = 'Here you can manage the backend for your website, manage your admin users, request new changes to be made to your site and much more...';
$linkData = \Modules\Admin\Services\Helper\MenuHelper::getRouteByPage();
@endphp
@if (!empty($linkData['category_description'])) 
 $headerDescription = $linkData['category_description']; 
@endif


@section('title')
@if($linkData['page_header'])
{{ $linkData['page_header'] }}
@else
Administrator
@endif
@stop
@section('main')
<div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <a href="{!! URL::to('/admin') !!}">
                <img src="{!! URL::asset('images/logo.png') !!}" alt="iprogrammer solutions pvt ltd" class="logo-default img-responsive" />
            </a>
            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>

        <div class="head-text-main col-lg-8 text-center hidden-sm hidden-xs">
            <span class="head-text-1 red-intense">{!! config('settings.C_SITENAME'); !!} Admin Panel</span>
            <div class="head-text-2">{{$headerDescription}}</div>
        </div>

        <div class="page-top">
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" id="user-login-info">
                            @include('admin::myprofile.userlogin_info')

                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{!! URL::to('admin/myprofile') !!}"><i class="icon-user"></i> My Profile </a>
                            </li>
                            <li>
                                <a href="{!! URL::to('admin/auth/logout') !!}"> <i class="icon-key"></i> Log Out </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<div class="clearfix"></div>

<div class="page-container">
    <div class="page-sidebar-wrapper">
        <div id="sidebar-menu" class="page-sidebar md-shadow-z-2-i  navbar-collapse collapse">
            @include('admin::layouts.sidebar')
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="page-content">
            @yield('content')
        </div>
    </div>
</div>

<div class="page-footer">
    <div class="page-footer-inner">
        {!! date('Y') !!} &copy; iProgrammer Solutions Pvt. Ltd.
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>

@stop