@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/segment-details.js') ) !!}
@stop

@section('scripts')
@parent
<script>
    jQuery(document).ready(function () {
        siteObjJs.admin.segmentDetailsJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::segment-details.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">{!! trans('admin::messages.view-name',['name'=> 'Segment Dtails']) !!}</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">{!! trans('admin::messages.add-name',['name'=> 'Segment Detail']) !!} </span></a>
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
            <table class="table table-striped table-bordered table-hover" id="segment-details-table" style="word-break: break-all;">
                <thead>
                    <tr role="row" class="heading">
                        <th width='1%'>#</th>
                        <th></th>
                        <th width='15%'>Route Name</th>
                        <th width='15%'>Segment Name</th>
                        <th width='50%'>Route Name</th>
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