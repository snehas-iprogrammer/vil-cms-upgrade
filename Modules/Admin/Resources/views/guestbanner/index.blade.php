@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/guestbanner.js') ) !!}
@stop

@section('scripts')
@parent
<script>
    jQuery(document).ready(function () {
        siteObjJs.admin.guestBannersJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::guestbanner.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">{!! trans('admin::messages.view-name',['name'=> 'Guest Banner']) !!}</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">{!! trans('admin::messages.add-name',['name'=> 'Guest Banner']) !!} </span></a>
        </div>
        @endif
    </div>
    <div class="table-actions-wrapper">
        <button class="btn btn-sm red table-group-action-delete"><i class="fa fa-trash"></i> {!! trans('admin::messages.delete') !!}</button>
        {!!  Form::select('status', ['' => 'Change Status',1 => trans('admin::messages.active'), 0 =>trans('admin::messages.inactive') ], null, ['required', 'class'=>'table-group-action-input form-control input-inline input-small input-sm'])!!}
        <button class="btn btn-sm yellow table-group-action-copy"><i class="fa fa-copy"></i> {!! trans('admin::messages.copy') !!}</button>
    </div>
    <div class="portlet-body">
        <div class="table-container">
            <div class="table-actions-wrapper">
                <span></span>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <!-- <input id="data-search" type="search" class="form-control" placeholder="Search"> -->
            </div>
            <table class="table table-striped table-bordered table-hover" id="guest-banner-table">
                <thead>
                    <tr role="row" class="heading">
                        <th width='1%'><input type="checkbox" value="All" id="chkAll" class="form-check-input chkAll"  /></th>
                        <th width='10%'>Banner Image</th>
                        <th width='10%'>Banner Title</th>
                        <th width='15%'>APP Version</th>
                        <th width='10%'>Banner Screen</th>
                        <th width='5%'>OS</th>
                        <th width="10%">Rank</th>
                        <th width="10%">Updated Dt</th>
                        <th width="10%">Status</th>
                        <th width='15%'>Options</th>
                    </tr>
                    @include('admin::guestbanner.search')
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
