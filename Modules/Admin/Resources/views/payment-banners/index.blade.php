@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/payment-banner.js') ) !!}
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
        siteObjJs.admin.paymentBannersJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
        siteObjJs.admin.paymentBannersJs.deleteConfirmMessage = "{!! trans('admin::messages.delete-confirm') !!}";
        siteObjJs.admin.paymentBannersJs.defaultImage = "{!! URL::asset('images/default-offer-category-icon.png ') !!}";
        siteObjJs.admin.paymentBannersJs.maxFileSize = "Maximum file size allowed is 800kb only.";
        siteObjJs.admin.paymentBannersJs.mimes = "{!! trans('admin::messages.mimes') !!}";
        siteObjJs.admin.paymentBannersJs.confirmRemoveImage = "Are you sure you want to remove this image? ";
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::payment-banners.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">View Payment Banners</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">Add New Banner </span></a>
        </div>
        @endif
    </div>
    <div class="portlet-body">
        <div class="table-container">
            <div class="table-actions-wrapper">
                <span>
                </span>
                <table class="">
                    <tbody>
                    <td>
                        
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input id="data-search" type="search" class="form-control" placeholder="Search">
                    </td>
                    </tbody>
                </table>
            </div>
            <table class="table table-striped table-bordered table-hover" id="PaymentBannersList">
                <thead>
                    <tr role="row" class="heading">
                        <th>#</th>
                        <th>LOB</th>
                        <th>Thumbnail Images</th>
                        <th>Rank</th>
                        <th>Added Date</th>
                        <th>Status</th>
                        <th width='15%'>Options</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
