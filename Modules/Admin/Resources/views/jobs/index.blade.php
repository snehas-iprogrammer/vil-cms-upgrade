@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/jobs.js') ) !!}
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
        siteObjJs.admin.jobsJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
        siteObjJs.admin.jobsJs.deleteConfirmMessage = "{!! trans('admin::messages.delete-confirm') !!}";
        siteObjJs.admin.jobsJs.defaultImage = "{!! URL::asset('images/default-offer-category-icon.png ') !!}";
        siteObjJs.admin.jobsJs.maxFileSize = "Maximum file size allowed is 800kb only.";
        siteObjJs.admin.jobsJs.mimes = "{!! trans('admin::messages.mimes') !!}";
        siteObjJs.admin.jobsJs.confirmRemoveImage = "Are you sure you want to remove this image? ";
    });
</script>
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::jobs.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">View Jobs Details</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">Add New Jobs Details </span></a>
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
                <span>
                </span>
                <table class="">
                    <tbody>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    </td>
                    </tbody>
                </table>
            </div>
            <table class="table table-striped table-bordered table-hover" id="jobsList" style="word-break: break-all;">
                <thead>
                    <tr role="row" class="heading">
                        <th><input type="checkbox" value="All" id="chkAll" class="form-check-input chkAll"  /></th>
                        <th width="5%">Name</th> 
                        <th width="5%">Label</th>
                        <th width="10%">Title</th>  
                        <th width="20%">Link</th>
                        <th width="5%">LOB</th>                        
                        <th width="10%">Circle</th> 
                        <th width="10%">Versions</th>
                        <th width="5%">OS</th>
                        <th width="5%">Rank</th>
                        <th width="5%">Updated Date</th>
                        <th width="5%">Status</th>
                        <th width='10%'>Options</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
