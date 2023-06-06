@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/ideatuesday.js') ) !!}
@stop

 @section('page-level-styles')
@parent
<style type="text/css">
    .table td .img-responsive {
    width: 100px !important; height: 60px !important;
}
</style>
@stop

@section('scripts')
@parent

<script>
    jQuery(document).ready(function () {
        siteObjJs.admin.ideaTuesdayJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
        siteObjJs.admin.ideaTuesdayJs.deleteConfirmMessage = "{!! trans('admin::messages.delete-confirm') !!}";
        siteObjJs.admin.ideaTuesdayJs.defaultImage = "{!! URL::asset('images/default-offer-category-icon.png ') !!}";
        siteObjJs.admin.ideaTuesdayJs.maxFileSize = "Maximum file size allowed is 800kb only.";
        siteObjJs.admin.ideaTuesdayJs.mimes = "{!! trans('admin::messages.mimes') !!}";
        siteObjJs.admin.ideaTuesdayJs.confirmRemoveImage = "Are you sure you want to remove this image? ";
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::ideatuesday.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}

@stop
