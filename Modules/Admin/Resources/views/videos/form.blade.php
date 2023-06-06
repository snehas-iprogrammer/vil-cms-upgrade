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
                <label class="col-md-4 control-label">Login Type<span class="required" aria-required="true"></span></label>
                <div class="col-md-8">
                    {!! Form::select('login_type', [''=> 'Select Login Type'] + $loginTypeList, null,['class'=>'select2me form-control', 'id' => 'login_type', 'data-rule-required'=>'false', 'data-msg-required'=>'Please select Login Type.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.circle') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    <!--{!! Form::select('circle', [''=> trans('admin::messages.select-circle', [ 'name' => trans('admin::controller/banner.select-circle') ])] + $circleList , null,['class'=>'select2me form-control', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select circle.']) !!}-->
                    {!! Form::select('circle[]', $circleList, $selectedCirclesArray,['multiple'=>'multiple','class'=>'select2me form-control circle', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Circle'])]) !!}  
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.lob') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('lob', [''=> trans('admin::messages.select-lob', [ 'name' => trans('admin::controller/banner.lob') ])] + $lobList, null,['class'=>'select2me form-control', 'id' => 'lob', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select lob.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.brand') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('brand', [''=> trans('admin::messages.select-brand', [ 'name' => trans('admin::controller/banner.select-brand') ])] + $brandList , null,['class'=>'select2me form-control', 'id' => 'brand', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select brand.']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.prepaid_persona') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('prepaid_persona[]', $prepaidPersonaList, $selectedprepaidPersonaArray,['multiple'=>'multiple','class'=>'select2me form-control prepaid_persona', 'id' => 'prepaid_persona']) !!}  
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">Prepaid Plans</label>
                <div class="col-md-8">
                    {!! Form::select('plan', [''=> 'Select a Plan'] + $planList , null,['class'=>'select2me form-control', 'id' => 'plan', 'data-rule-required'=>'false', 'data-msg-required'=>'Please select Plan.']) !!}  
                </div>
            </div>
            
        </div>
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.postpaid_persona') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('postpaid_persona[]', $postpaidPersonaList, $selectedpostpaidPersonaArray,['multiple'=>'multiple','class'=>'select2me form-control postpaid_persona', 'id' => 'postpaid_persona']) !!}  
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.socid') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('socid', null, ['class'=>'form-control', 'id'=>'socid']) !!}  
                    <span class="help-block">Enter Socid Comma Seprated. Eg. 25826505,25826506</span>
                </div>
            </div>
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
                <label class="col-md-4 control-label">Video Title<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('video_title', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter video title.']) !!}
                    <span class="help-block">Enter video title Eg. Postpaid Plan</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Video Link<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('video_link', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter video link.']) !!}
                    <span class="help-block">Enter Video's S3 Bucket public URL. </span>
                </div>
            </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">Link Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    @if($action == 'update')
                        <?php
                            $linkType = '';
                            if($videos->internal_link != ''){ $linkType = 1; }
                            if($videos->external_link != ''){ $linkType = 2; }
                        ?>
                        {!! Form::select('link_type', ['' => 'Select Link Type']+$linkTypeList , $linkType, ['class'=>'select2me form-control', 'id' => 'link_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select link type.']) !!}
                    @else
                        {!! Form::select('link_type', ['' => 'Select Link Type']+$linkTypeList , null,['class'=>'select2me form-control', 'id' => 'link_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select link type.']) !!}
                    @endif    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">

            </div>
        </div>        
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.internal-link') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('internal_link', null, ['class'=>'form-control', 'id'=>'internal_link','data-rule-maxlength' => '255']) !!}
                    <span class="help-block">Enter internal link Eg. /recharge</span>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.external-link') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('external_link', null, ['class'=>'form-control', 'id'=>'external_link', 'data-rule-maxlength' => '255']) !!}
                    <span class="help-block">Enter external link Eg. http://myvi.mydala.com</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">CTA Name</label>
                <div class="col-md-8">
                    {!! Form::text('cta_name', null, ['class'=>'form-control', 'id'=>'cta_name','data-rule-maxlength' => '100']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.device-os') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('device_os', [''=> trans('admin::messages.select-banner-os', [ 'name' => trans('admin::controller/banner.select-device-os') ])] + $osList , null,['class'=>'select2me form-control', 'id' => 'device_os', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select device os.']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.appversion') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('app_version[]', $appVersionList, $selectedAppVersionArray,['multiple'=>'multiple','class'=>'select2me form-control app_version', 'id' => 'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'app_version'])]) !!}  
                </div>
            </div>
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
