<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">Prepaid Circles JSON<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::textarea('prepaid_circles', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'prepaid_circles', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Prepaid Circles JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Prepaid Circles JSON']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Postpaid Circles JSON<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::textarea('postpaid_circles', null, ['minlength'=>2, 'rows' => 5, 'class'=>'form-control', 'id'=>'postpaid_circles', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Prepaid Circles JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Prepaid Circles JSON']) ])!!}
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