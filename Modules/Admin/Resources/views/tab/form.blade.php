<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">Tab Name</label>
        <div class="col-md-8">
        {!! Form::text('name', null, ['class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter name.' ])!!}
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