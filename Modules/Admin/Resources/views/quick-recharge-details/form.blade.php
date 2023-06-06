<div class="form-body">
    <div class="form-group">
        <label class="col-md-3 control-label">Route Name<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('route_name', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'route_name', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Route Name']), 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'Route Name']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Route Name']) ])!!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">{!! trans('admin::controller/banner.circle') !!}<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            <!--{!! Form::select('circle', [''=> trans('admin::messages.select-circle', [ 'name' => trans('admin::controller/banner.select-circle') ])] + $circleList , null,['class'=>'select2me form-control', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select circle.']) !!}-->
            {!! Form::select('circle[]', $circleList, $selectedCirclesArray,['multiple'=>'multiple','class'=>'select2me form-control circle', 'id' => 'circle', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Circle'])]) !!}  
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">MRP<span class="required" aria-required="true">*</span></label>
        <div class="col-md-8">
            {!! Form::text('mrp', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'mrp', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'MRP']), 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>'MRP']) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'MRP']) ])!!}
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