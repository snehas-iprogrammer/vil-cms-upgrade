@extends('admin::layouts.master')

@section('template-level-scripts')
@parent
{!! HTML::script( URL::asset('js/admin/gallery.js') ) !!}
@stop

@section('page-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') ) !!}
{!! HTML::style( URL::asset('css/admin/admin-user.css') ) !!}
<style type="text/css">
    tr > td:nth-child(6) { word-break: break-all !important; }
    .table td .img-responsive{height: 100px !important;}

    .ajax-loader { visibility: hidden;background-color: rgba(255,255,255,0.7);position: fixed;z-index: +500 !important;width: 100%;height:100%;}
    .ajax-loader img {position: relative;top:25%;left:30%;} 
    
    .ajax-loader-edit { visibility: hidden;background-color: rgba(255,255,255,0.7);position: fixed;z-index: +500 !important;width: 100%;height:100%;}
    .ajax-loader-edit img {position: relative;top:25%;left:30%;} 
</style>
@stop

@section('scripts')
@parent
<script>
    jQuery(document).ready(function () {
        $('form:first *:input[type!=hidden]:first').focus();
        siteObjJs.admin.galleryJs.init();
        siteObjJs.admin.commonJs.boxExpandBtnClick();
        siteObjJs.admin.galleryJs.defaultImage = "{!! URL::asset('images/default-offer-category-icon.png ') !!}";
        siteObjJs.admin.galleryJs.maxFileSize = "Maximum file size allowed is 800kb only.";
        siteObjJs.admin.galleryJs.mimes = "{!! trans('admin::messages.mimes') !!}";
        siteObjJs.admin.galleryJs.confirmRemoveImage = "Are you sure you want to remove this image? ";
    });
</script>
@stop

@section('page-level-scripts')
@parent
{!! HTML::script( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ) !!}
@stop

@section('content')
@include('admin::partials.breadcrumb')
<div id="ajax-response-text"></div>

@if(!empty(Auth::user()->hasAdd))
@include('admin::gallery.create')
@endif
{{--*/ $linkIcon = \Modules\Admin\Services\Helper\MenuHelper::getSelectedPageLinkIcon() /*--}}
<div id="edit_form">

</div>

<div class="portlet light col-lg-12">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa {{$linkIcon}} font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase">{!! trans('admin::messages.view-name',['name'=>trans('gallery')]) !!}</span>
        </div>
        @if(!empty(Auth::user()->hasAdd))
        <div class="actions">
            <a href="javascript:;" class="btn blue btn-add-big btn-expand-form"><i class="fa fa-plus"></i><span class="hidden-480">{!! trans('admin::messages.add-name',['name'=>trans('gallery')]) !!} </span></a>
        </div>
        @endif
    </div>
    <div class="portlet-body">
        <div class="table-container">
            <div class="table-actions-wrapper">
                <span></span>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <!--<input id="data-search" type="search" class="form-control" placeholder="Search">-->
            </div>
            <table class="table table-striped table-bordered table-hover" id="testimonials-table">
                <thead>
                    <tr role="row" class="heading">
                        <th width='1%'>#</th>
                        <th></th>
                        <th width='20%'>Title</th>
                        <th width='20%'>Thumbnail Images</th>
                        <th width='20%'>Image</th>
                        <th width='10%'>Order</th>
                        <th width='10%'>Status</th>
                        <th width='15%'>Action</th>
                    </tr>
                    <tr role="row" class="filter">                        
                        <td width='1%'></td>
                        <td></td>
                        <td width='20%'>{!! Form::text('title', null, ['id'=> 'title', 'class'=>'form-control form-filter', 'column-index' => '4']) !!}</td>
                        <td width='20%'></td>
                        <td width='20%'></td>
                        <td width='10%'>{!! Form::text('ordera', null, ['id'=> 'ordera', 'class'=>'form-control form-filter', 'column-index' => '4']) !!}</td>
                        <td width='10%'>                
                            {!! Form::select('status', [''=>'Select Status', '1'=>'Active', '0'=>'Inactive'] , null, ['required', 'style'=>'width: 125px;', 'id' => 'status', 'class'=>'form-control form-filter input-sm'])!!}
                        </td>      
                        <td width='15%'>       
                            <button class="btn btn-sm yellow filter-submit margin-bottom-5" title="Search" id="search_prod" ><i class="fa fa-search"></i></button>
                            <button class="btn btn-sm red filter-cancel margin-bottom-5" title="Reset"><i class="fa fa-times"></i></button>
                        </td>                        
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
