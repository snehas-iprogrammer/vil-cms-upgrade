<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">App Version<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('app_version', $appVersionList, null,['class'=>'select2me form-control app_version', 'id' => 'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'App Version'])]) !!}  
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Silent OTA<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('silent_ota', $silentOtaList , null,['class'=>'select2me form-control', 'id' => 'silent_ota' , 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Silent OTA'])]) !!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">New Features</label>
        <div class="col-md-8">
            {!! Form::textarea('new_features', null, ['minlength'=>2, 'id' => 'new_features', 'size' => '30x3','class'=>'form-control text-noresize', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter New Features.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'New Features']) ])!!}
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