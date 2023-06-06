<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">Screen ID<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('screen_id', null, ['minlength'=>2,'maxlength'=>100,'class'=>'form-control', 'id'=>'screen_id', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen ID']), 'data-rule-maxlength'=>'100', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Screen ID']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen ID']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Screen Header<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('screen_header', null, ['minlength'=>2,'maxlength'=>100,'class'=>'form-control', 'id'=>'screen_header', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen Header']), 'data-rule-maxlength'=>'100', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Screen Header']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen Header']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Screen Title<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('screen_title', null, ['minlength'=>2,'maxlength'=>100,'class'=>'form-control', 'id'=>'screen_title', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen Title']), 'data-rule-maxlength'=>'100', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Screen Title']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen Title']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Screen Description<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::textarea('screen_description', null, ['minlength'=>3, 'rows' => 10, 'class'=>'form-control', 'id'=>'screen_description', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen Description']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen Description']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Screen Pack Title<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('screen_packs_title', null, ['minlength'=>2,'maxlength'=>100,'class'=>'form-control', 'id'=>'screen_packs_title', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen Pack Title']), 'data-rule-maxlength'=>'100', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Screen Pack Title']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen Pack Title']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Screen Packs Button Text<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('screen_packs_button_txt', null, ['minlength'=>2,'maxlength'=>100,'class'=>'form-control', 'id'=>'screen_packs_button_txt', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen Packs Button Text']), 'data-rule-maxlength'=>'100', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Screen Packs Button Text']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen Packs Button Text']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Screen Packs Button Link<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('screen_packs_button_link', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'screen_packs_button_link', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Screen Packs Button Link']), 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Screen Packs Button Link']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Screen Packs Button Link']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">FAQs JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('faqs_json', null, ['minlength'=>2, 'rows' => 10, 'class'=>'form-control', 'id'=>'faqs_json', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'FAQs JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'FAQs JSON']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">MRPs JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('mrps_json', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'mrps_json', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'MRPs JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'MRPs JSON']) ])!!}
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