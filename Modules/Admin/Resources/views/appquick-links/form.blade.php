@section('global-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/uniform/css/uniform.default.min.css') ) !!}
@stop

@section('page-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') ) !!}
{!! HTML::style( URL::asset('css/admin/admin-user.css') ) !!}

@stop

@section('page-level-scripts')
@parent
{!! HTML::script( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ) !!}
@stop
<div class="form-body">
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.circle') !!}</label>
                <div class="col-md-8">
                    <!--{!! Form::select('circle', [''=> trans('admin::messages.select-circle', [ 'name' => trans('admin::controller/banner.select-circle') ])] + $circleList , null,['class'=>'select2me form-control', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select circle.']) !!}-->
                    {!! Form::select('circle[]', $circleList, $selectedCirclesArray,['multiple'=>'multiple','class'=>'select2me form-control circle', 'id' => 'circle']) !!}  
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.brand') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('brand', [''=> trans('admin::messages.select-brand', [ 'name' => trans('admin::controller/banner.select-brand') ])] + $brandList , null,['class'=>'select2me form-control', 'id' => 'brand','data-msg-required'=>'Please select brand.']) !!}
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.socid_include_exclude') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('socid_include_exclude', $socidIncludeExcludeList , null,['class'=>'select2me form-control', 'id' => 'socid_include_exclude']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.socid') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('socid', null, ['class'=>'form-control', 'id'=>'socid']) !!}  
                    <span class="help-block">Enter Socid Comma Seprated. Eg. 25826505,25826506</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">Login Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('login', $loginTypeList, null,['class'=>'select2me form-control', 'id' => 'login', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Login.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">Plan</label>
                <div class="col-md-8">
                    {!! Form::select('plan', $planList, null,['class'=>'select2me form-control', 'id' => 'plan', 'data-rule-required'=>'false', 'data-msg-required'=>'Please select Login.']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.appversion') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('app_version[]', $appVersionList, $selectedAppVersionArray,['multiple'=>'multiple','class'=>'select2me form-control app_version', 'id' => 'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'app_version'])]) !!}  
                </div>
            </div>
        </div>
        
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">Red Hierarchy</label>
                <div class="col-md-8">
                    {!! Form::select('red_hierarchy[]', $redHierarchyList, $selectedRedHierarchyArray, ['multiple'=>'multiple','class'=>'select2me form-control', 'id' => 'red_hierarchy']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">{!! "Quick Link" !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('quicklink_id', $masterQuickLinkList, $selectedMasterQuickLinkArray,['class'=>'select2me form-control quicklink_id', 'id' => 'quicklink_id', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Quicklink id'])]) !!}  
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.rank') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('rank', [''=> trans('admin::messages.select-rank', [ 'name' => trans('admin::controller/banner.select-banner-rank') ])] + $rankList, null,['class'=>'select2me form-control', 'id' => 'rank', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select rank.']) !!}
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
