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
                <label class="control-label col-md-4">{!! trans('admin::controller/banner.select-screen') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('banner_screen', [''=> trans('admin::messages.select-screen', [ 'name' => trans('admin::controller/banner.select-screen') ])] + $screenList , null,['class'=>'select2me form-control', 'id' => 'banner_screen', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select banner screen.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Login Type<span class="required" aria-required="true"></span></label>
                <div class="col-md-8">
                    {!! Form::select('login_type', [''=> 'Select Login Type'] + $loginTypeList, null,['class'=>'select2me form-control', 'id' => 'login_type', 'data-rule-required'=>'false', 'data-msg-required'=>'Please select Login Type.']) !!}
                </div>
            </div>
        </div>
    </div>
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.lob') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('lob', [''=> trans('admin::messages.select-lob', [ 'name' => trans('admin::controller/banner.lob') ])] + $lobList, null,['class'=>'select2me form-control', 'id' => 'lob', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select lob.']) !!}
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.socid_include_exclude') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('socid_include_exclude', $socidIncludeExcludeList , null,['class'=>'select2me form-control', 'id' => 'socid_include_exclude']) !!}
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.banner-title') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('banner_title', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter banner title.']) !!}
                    <span class="help-block">Enter banner title Eg. Postpaid Plan</span>
                </div>
            </div>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Subtitle</label>
                <div class="col-md-8">
                    {!! Form::text('subtitle', null, ['class'=>'form-control', 'data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Analytics tag<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('analytics_tag', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter analytics_tag.']) !!}

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.internal-link') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('internal_link', null, ['class'=>'form-control', 'id'=>'internal_link','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">Start Date<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 date form_datetime">
                    {!! Form::text('start_date_time', null, ['class'=>'form-control', 'style' => 'width: 258px; display: inline;','id'=>'start_date_time','placeholder'=>'Start Time', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select start date.']) !!}
                    <span class="input-group-btn" style="display: -webkit-inline-box; vertical-align: bottom; ">
                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                        <button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">Red Hierarchy<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('red_hierarchy[]',$redHierarchyList,$selectedRedhierarchyArray,['multiple'=>'multiple','class'=>'select2me form-control', 'id' => 'red_hierarchy']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Price</label>
                <div class="col-md-8">
                    {!! Form::text('price', null, ['class'=>'form-control', 'id'=>'internal_link','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">End Date<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 date form_datetime">
                    {!! Form::text('end_date_time', null, ['class'=>'form-control', 'style' => 'width: 258px; display: inline;','id'=>'end_date_time','placeholder'=>'End Date', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select end date.']) !!}
                    <span class="input-group-btn" style="display: -webkit-inline-box; vertical-align: bottom; ">
                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                        <button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label col-md-4">Banner Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($livemusic->banner_name))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getBannerImagePath($livemusic->id, $livemusic->banner_name) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-img.png'), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image', $livemusic->banner_name, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($livemusic->banner_name))
                                    {!! trans('admin::controller/banner.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/banner.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($livemusic->banner_name))
                                    {!! Form::file('image', ['id' => 'avatar' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('image', ['id' => 'avatar', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}
                                @endif

                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($livemusic->banner_name))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.banner-rank') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('banner_rank', [''=> trans('admin::messages.select-banner-rank', [ 'name' => trans('admin::controller/banner.select-banner-rank') ])] + $rankList, null,['class'=>'select2me form-control', 'id' => 'banner_rank', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select banner rank.']) !!}
                </div>
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
