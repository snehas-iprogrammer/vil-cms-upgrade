<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">LOB<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::select('lob', [''=> trans('admin::messages.select-lob', [ 'name' => trans('admin::controller/banner.lob') ])] + $lobList, null,['class'=>'select2me form-control', 'id' => 'lob', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select lob.']) !!} 
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Title<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('title', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'title', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Title.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Title']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Header Text<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::textarea('header_text', null, ['minlength'=>2,'size' => '30x3',  'id'=>'header_text','class'=>'form-control text-noresize', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Header Text.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Text Text']) ])!!}
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-3 control-label">CTA<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('cta', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'cta', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter CTA.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'CTA']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">CTA Description<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::textarea('cta_description', null, ['minlength'=>2,'size' => '30x3',  'id'=>'cta_description','class'=>'form-control text-noresize', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter CTA Description.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'CTA Description']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">CTA Internal Link<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('cta_internal_link', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'cta_internal_link', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter CTA Internal Link.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'CTA Internal Link']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">CTA External Link<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('cta_external_link', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'cta_external_link', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter CTA External Link.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'CTA External Link']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Claim Reward CTA<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('claim_rewards_cta', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'claim_rewards_cta', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter Claim Reward CTA.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Claim Reward CTA']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Claim Reward Text<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::textarea('claim_rewards_text', null, ['minlength'=>2,'size' => '30x3',  'id'=>'claim_rewards_text','class'=>'form-control text-noresize', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter Claim Reward Text.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Claim Reward Text']) ])!!}
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-3 control-label">Claim Internal Link<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('claim_internal_link', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'claim_internal_link', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter Claim Internal Link.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Claim Internal Link']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Claim External Link<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
             {!! Form::text('claim_external_link', null, ['minlength'=>2,'class'=>'form-control', 'id'=>'claim_external_link', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter Claim External Link.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Claim External Link']) ])!!}
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-3 control-label">Banners JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('banners_json', null, ['minlength'=>2, 'rows' => 10, 'class'=>'form-control', 'id'=>'banners_json', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Banners JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Banners JSON']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Partner Banners JSON<span class="required" aria-required="true"></span></label>
        <div class="col-md-8">
            {!! Form::textarea('partner_banners_json', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'partner_banners_json', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Partner Banners JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Partner Banners JSON']) ])!!}
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