@section('global-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/uniform/css/uniform.default.min.css') ) !!}
@stop

@section('page-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') ) !!}
{!! HTML::style( URL::asset('css/admin/admin-user.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ) !!}
@stop

@section('page-level-scripts')
@parent
{!! HTML::script( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ) !!}
@stop
<div class="form-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.circle') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    <!--{!! Form::select('circle', [''=> trans('admin::messages.select-circle', [ 'name' => trans('admin::controller/banner.select-circle') ])] + $circleList , null,['class'=>'select2me form-control', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select circle.']) !!}-->
                    {!! Form::select('circle[]', $circleList, $selectedCirclesArray,['multiple'=>'multiple','class'=>'select2me form-control circle', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Circle'])]) !!}  
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.lob') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('lob', [''=> trans('admin::messages.select-lob', [ 'name' => trans('admin::controller/banner.lob') ])] + $lobList, null,['class'=>'select2me form-control', 'id' => 'lob', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select lob.']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Login Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('login_type', [''=> 'Select Login Type'] + $loginTypeList, null,['class'=>'select2me form-control', 'id' => 'login_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Login Type.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.prepaid_persona') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('prepaid_persona[]', $prepaidPersonaList, $selectedprepaidPersonaArray,['multiple'=>'multiple','class'=>'select2me form-control prepaid_persona', 'id' => 'prepaid_persona']) !!}  
                </div>
            </div>  
        </div>    
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.postpaid_persona') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('postpaid_persona[]', $postpaidPersonaList, $selectedpostpaidPersonaArray,['multiple'=>'multiple','class'=>'select2me form-control postpaid_persona', 'id' => 'postpaid_persona']) !!}  
                </div>
            </div>
        </div>
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.socid_include_exclude') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('socid_include_exclude', $socidIncludeExcludeList , null,['class'=>'select2me form-control', 'id' => 'socid_include_exclude']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.socid') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('socid', null, ['class'=>'form-control', 'id'=>'socid']) !!}  
                    <span class="help-block">Enter Socid Comma Seprated. Eg. 25826505,25826506</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Title<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('title', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter title.']) !!}
                    <span class="help-block">Enter  title Eg. Postpaid Plan</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Label<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('label', [''=> 'Select Label'] + $labelList, null,['class'=>'select2me form-control', 'id' => 'label', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Label.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Description</label>
                <div class="col-md-8">
                    {!! Form::textarea('description', null, ['rows' => 4, 'class'=>'form-control', 'id'=>'description'])!!}
                </div>
            </div>      
        </div>
    </div>       
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Name<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('name', null, ['class'=>'form-control','data-rule-required'=>'true', 'id'=>'name','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">URL<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('url', null, ['class'=>'form-control','data-rule-required'=>'true', 'id'=>'internal_link','data-rule-maxlength' => '255']) !!}
                    <span class="help-block">Enter internal link Eg. /recharge</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Dashboard Logo<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('dashboard_logo', null, ['class'=>'form-control','data-rule-required'=>'true', 'id'=>'dashboard_logo','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Red Hierarchy</label>
                <div class="col-md-8">
                    {!! Form::select('red_hierarchy', [''=> 'Select Red Hierarchy'] + $redHierarchyList , null,['class'=>'select2me form-control', 'id' => 'red_hierarchy']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Internal Link<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('internal_link', null, ['class'=>'form-control','data-rule-required'=>'true', 'id'=>'internal_link']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.device-os') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('device_os', [''=> trans('admin::messages.select-banner-os', [ 'name' => trans('admin::controller/banner.select-device-os') ])] + $osList , null,['class'=>'select2me form-control', 'id' => 'device_os', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select device os.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.appversion') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('app_version[]', $appVersionList, $selectedAppVersionArray,['multiple'=>'multiple','class'=>'select2me form-control app_version', 'id' => 'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'app_version'])]) !!}  
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.banner-rank') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('rank', [''=> trans('admin::messages.select-banner-rank', [ 'name' => trans('admin::controller/banner.select-banner-rank') ])] + $rankList, null,['class'=>'select2me form-control', 'id' => 'rank', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select rank.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Youtube Link</label>
                <div class="col-md-8">
                    {!! Form::text('youtube_link', null, ['class'=>'form-control', 'data-rule-maxlength' => '255']) !!}
                   
                </div>
            </div>
        </div>
        </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Status <span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    <div class="radio-list">
                        <label class="radio-inline">{!! Form::radio('status', '1', true) !!} {!! trans('admin::messages.active') !!}</label>
                        <label class="radio-inline">{!! Form::radio('status', '0') !!} {!! trans('admin::messages.inactive') !!}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
