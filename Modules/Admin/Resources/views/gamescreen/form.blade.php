<div class="form-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Gamescreen Name<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                {!! Form::text('name', null, ['class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter name.' ])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Section Name<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                {!! Form::text('section_name', null, ['class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter name.' ])!!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Description</label>
                <div class="col-md-8">
                    {!! Form::textarea('description', null, ['rows' => 4, 'class'=>'form-control', 'id'=>'description'])!!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Rank<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                {!! Form::select('rank', [''=> trans('admin::messages.select-banner-rank', [ 'name' => 'Select Rank' ])] + $rankList, null,['class'=>'select2me form-control', 'id' => 'rank', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select rank.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
    </div>
</div>