@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/recharge-offers.js') ) !!}
@stop

@section('page-level-styles')
@parent
<style type="text/css">
td { word-break: break-word; }
</style>
@stop

@section('scripts')
@parent
<script>
    jQuery(document).ready(function () {
        siteObjJs.admin.rechargeOffersJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::recharge-offers.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">{!! trans('admin::messages.view-name',['name'=> 'Recharge Offers']) !!}</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">{!! trans('admin::messages.add-name',['name'=> 'Recharge offer']) !!} </span></a>
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
            <table class="table table-striped table-bordered table-hover" id="recharge-offers-table">
                <thead>
                    <tr role="row" class="heading">
                        <th width='1%'>#</th>
                        <th></th>
                        <th width='10%'>Segment Name</th>
                        <th width='10%'>Route Name</th>
                        <th width='40%'>Recommended Packs JSON</th>
                        <th width='25%' style="word-break: break-word;">MRP Sequence Data</th>
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
