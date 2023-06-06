<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">Route Name<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('route_name', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'route_name', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Route Name']), 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Route Name']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Route Name']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Segment Name<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('segment_name', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'segment_name', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Segment Name']), 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Segment Name']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Segment Name']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Referred JSON<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::textarea('referred_json', null, ['minlength'=>2, 'rows' => 10, 'class'=>'form-control', 'id'=>'referred_json', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Referred JSON']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Referred JSON']) ])!!}
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