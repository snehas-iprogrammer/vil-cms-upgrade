<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">Circle<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('circle[]', [''=> 'Select Circle'] + $circleList, $selectedCirclesArray,['multiple'=>'multiple','class'=>'select2me form-control circle', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Circle'])]) !!}  
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Brand<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('brand', [''=> trans('admin::messages.select-brand', [ 'name' => trans('admin::controller/banner.select-brand') ])] + $brandList , null,['class'=>'select2me form-control', 'id' => 'brand', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select brand.']) !!} 
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">LOB<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('lob', [''=> trans('admin::messages.select-lob', [ 'name' => trans('admin::controller/banner.lob') ])] + $lobList, null,['class'=>'select2me form-control', 'id' => 'lob', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select lob.']) !!} 
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Prepaid Persona<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('prepaid_persona[]', $prepaidPersonaList , null,['class'=>'select2me form-control', 'id' => 'prepaid_persona']) !!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Postpaid Persona</label>
        <div class="col-md-8">
            {!! Form::select('postpaid_persona[]', $postpaidPersonaList, $selectedpostpaidPersonaArray,['multiple'=>'multiple','class'=>'select2me form-control postpaid_persona', 'id' => 'postpaid_persona']) !!}  
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Red Hierarchy</label>
        <div class="col-md-8">
        {!! Form::select('red_hierarchy[]', $redHierarchyList, $selectedredhierarchyArray,['multiple'=>'multiple','class'=>'select2me form-control red_hierarchy', 'id' => 'red_hierarchy']) !!}  
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Login Type<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::select('login_type', [''=> 'Select Login Type'] + $loginTypeList, null,['class'=>'select2me form-control', 'id' => 'login_type', 'data-rule-required'=>'false', 'data-msg-required'=>'Please select Login Type.']) !!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">App Version<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('app_version[]', $appVersionList, $selectedAppVersionArray,['multiple'=>'multiple','class'=>'select2me form-control app_version', 'id' => 'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'app_version'])]) !!}
        </div>
    </div>
    <!-- <div class="form-group">
        <label class="col-md-3 control-label">Header Menu JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('header_menu', null, ['minlength'=>2, 'rows' => 10, 'class'=>'form-control', 'id'=>'header_menu', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Header Menu']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Header Menu JSON']) ])!!}
        </div>
    </div> -->
    <div class="form-group">
        <label class="col-md-3 control-label">Active Tab For Lottie</label>
        <div class="col-md-8">
                {!! Form::text('active_tab_for_lottie', null, ['class'=>'form-control', 'data-rule-maxlength' => '255']) !!}
                </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Rail Sequence JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('rail_sequence', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'rail_sequence', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Rail Sequence']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Rail Sequence JSON']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Rail Title JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('rail_titles', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'rail_titles', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Rail Title']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Rail Title JSON']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">New Dashboard Rail Sequence JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('new_dashboard_rail_sequence', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'new_dashboard_rail_sequence', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Rail Sequence']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Rail Sequence JSON']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">{!! trans('admin::controller/faq-category.status') !!}<span class="required" aria-required="true">*</span> </label>
        <div class="col-md-8">
            <div class="radio-list">
                <label class="radio-inline">{!! Form::radio('status', '1', true) !!} {!! trans('admin::messages.active') !!}</label>
                <label class="radio-inline">{!! Form::radio('status', '0') !!} {!! trans('admin::messages.inactive') !!}</label>
            </div>
        </div>
    </div>
</div>