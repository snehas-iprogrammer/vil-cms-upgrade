@section('global-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/uniform/css/uniform.default.min.css') ) !!}
@stop

@section('page-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') ) !!}
{!! HTML::style( URL::asset('css/admin/admin-user.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') ) !!}
{!! HTML::style( URL::asset('global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ) !!}
@stop

@section('page-level-scripts')
@parent
{!! HTML::script( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ) !!}
@stop
<div class="form-body">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Question<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                {!! Form::text('question', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter question.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="col-md-4 control-label">Answer1<span class="required" aria-required="true">*</span></label>
                    <div class="col-md-8">
                        {!! Form::text('answer1', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter answer1.']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Answer2<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('answer2', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter answer2.']) !!}
                </div>
            </div>
        </div>  
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Answer3<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('answer3', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter answer3.']) !!}
                </div>
            </div>
        </div>  
    </div> 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Answer4<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('answer4', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter answer4.']) !!}
                </div>
            </div>
        </div>  
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Correct Answer<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('correct_answer', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter correct_answer.']) !!}
                </div>
            </div>
        </div>  
    </div>  
    <div class="row">
        <div class="col-md-6">
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
    </div>    
</div>
