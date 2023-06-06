<div class="form-body">
    <div class="form-group">
        <label class="col-md-4 control-label">App Version<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            @if($from == 'create')
                {!! Form::text('app_version', null, ['minlength'=>2,'maxlength'=>150,'class'=>'form-control', 'id'=>'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'App Version']), 'data-rule-maxlength'=>'150', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=> 'App Version']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'App Version']) ])!!}
            @else
                {!! Form::text('app_version', null, ['readonly' => 'readonly','minlength'=>2,'maxlength'=>150,'class'=>'form-control', 'id'=>'app_version', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'App Version']), 'data-rule-maxlength'=>'150', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=> 'App Version']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'App Version']) ])!!}
            @endif 
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Text Message</label>
        <div class="col-md-8">
            {!! Form::text('text_msg', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'text_msg', 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=> 'Text Message']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Text Message']) ])!!}            
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Is Humgama Enabled<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('is_hungama_enabled', $isHungamaEnabledList, null,['class'=>'select2me form-control', 'id' => 'is_hungama_enabled', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Is Hungama Enabled.']) !!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Is ViMTV SDK Enabled<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('is_vimtv_sdk_enabled', $isViMTVSDKEnabledList, null,['class'=>'select2me form-control', 'id' => 'is_vimtv_sdk_enabled', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Is ViMTV SDK Enabled.']) !!}
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