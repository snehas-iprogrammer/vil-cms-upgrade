<label class="col-md-3 control-label">State <span class="required" aria-required="true">*</span></label>
<div class="col-md-4" id='state-listing-content'>
    {!! Form::select('state_id', [''=> trans('admin::messages.select-name',['name'=>trans('admin::controller/city.state')]) ] + $stateList, null,['class'=>'select2me form-control form-filter', 'id' => 'state_id', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select State.']) !!}
</div>