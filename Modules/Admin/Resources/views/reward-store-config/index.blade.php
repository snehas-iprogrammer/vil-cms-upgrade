@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/reward-store-config.js') ) !!}
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
        siteObjJs.admin.rewardStoreConfigJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
        siteObjJs.admin.anonScreenCarouselDetailsJs.deleteConfirmMessage = "{!! trans('admin::messages.delete-confirm') !!}";
        siteObjJs.admin.anonScreenCarouselDetailsJs.defaultImage = "{!! URL::asset('images/default-offer-category-icon.png ') !!}";
        siteObjJs.admin.anonScreenCarouselDetailsJs.maxFileSize = "Maximum file size allowed is 800kb only.";
        siteObjJs.admin.anonScreenCarouselDetailsJs.mimes = "{!! trans('admin::messages.mimes') !!}";
        siteObjJs.admin.anonScreenCarouselDetailsJs.confirmRemoveImage = "Are you sure you want to remove this image? ";
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::reward-store-config.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">{!! trans('admin::messages.view-name',['name'=> 'Reward Store Config']) !!}</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">{!! trans('admin::messages.add-name',['name'=> 'Reward Store Config']) !!} </span></a>
        </div>
        @endif
    </div>
    <div class="portlet-body">
        <div class="table-container">
            <div class="table-actions-wrapper">
                <span></span>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <input id="data-search" type="search" class="form-control" placeholder="Search">
            </div>
            <table class="table table-striped table-bordered table-hover" id="reward-store-config-table">
                <thead>
                    <tr role="row" class="heading">
                        <th width='1%'>#</th>
                        <th></th>
                        <th width='10%'>Title</th>
                        <th width='10%'>LOB</th>
                        <th width='10%'>Header Text</th>
                        <th width='10%'>CTA</th>
                        <th width='10%'>CTA Link</th>
                        <th width='25%'>Banners JSON</th>
                        <th width='25%'>Partner Banners JSON</th>
                        <th width='10%'>{!! trans('admin::controller/faq-category.status') !!}</th>
                        <th width='10%'>{!! trans('admin::controller/faq-category.action') !!}</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
