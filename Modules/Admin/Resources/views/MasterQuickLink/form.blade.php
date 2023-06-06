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
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.title') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('title', null, ['class'=>'form-control', 'data-rule-maxlength' => '100', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter title.']) !!}
                    <span class="help-block">Enter Title : Recharge </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.name') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('name', null, ['class'=>'form-control', 'data-rule-maxlength' => '100','id'=>'name', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter name.']) !!}
                    <span class="help-block">Enter name : Recharge </span>
                </div>
            </div>
        </div>
        
    </div> 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.TealiumEvents') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('TealiumEvents', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-msg-required'=>'Please enter TealiumEvents.']) !!}
                    <span class="help-block">Enter TealiumEvents Eg. Postpaid Plan</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">    
            <div class="form-group">
                <label class="col-md-4 control-label">Link Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    @if($action == 'update')
                        <?php
                            $linkType = '';
                            if($masterquicklink->internalLink != ''){ $linkType = 1; }
                            if($masterquicklink->externalLink != ''){ $linkType = 2; }
                        ?>
                        {!! Form::select('link_type', ['' => 'Select Link Type']+$linkTypeList , $linkType, ['class'=>'select2me form-control', 'id' => 'link_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select link type.']) !!}
                    @else
                        {!! Form::select('link_type', ['' => 'Select Link Type']+$linkTypeList , null,['class'=>'select2me form-control', 'id' => 'link_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select link type.']) !!}
                    @endif    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.internalLink') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('internalLink', null, ['class'=>'form-control', 'data-rule-maxlength' => '1000','id'=>'internalLink','data-msg-required'=>'Please enter device width.']) !!}
                    <span class="help-block">Enter internalLink : ContactList </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.externalLink') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('externalLink', null, ['class'=>'form-control','id'=>'externalLink','data-rule-maxlength' => '255']) !!}
                   
                </div>
            </div>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.sequenceNumber') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('sequenceNumber', null, ['class'=>'form-control', 'data-rule-maxlength' => '100']) !!}
                    <span class="help-block">Enter sequenceNumber : 1/2/3 </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.cardType') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('cardType', null, ['class'=>'form-control', 'data-rule-maxlength' => '255']) !!}
                   
                </div>
            </div>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Is Animated</label>
                <div class="col-md-8">
                    {!! Form::text('is_animated', null, ['class'=>'form-control', 'data-rule-maxlength' => '100']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/masterquicklink.tag') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('tag', null, ['class'=>'form-control', 'data-rule-maxlength' => '100']) !!}
                    <span class="help-block">Enter internalLink : ContactList </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label col-md-4">Quicklink Image</label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($masterquicklink->imageUrl))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getBannerImagePath($masterquicklink->id, $masterquicklink->imageUrl) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-img.png'), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image', $masterquicklink->imageUrl, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($masterquicklink->imageUrl))
                                    {!! trans('admin::controller/banner.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/banner.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($masterquicklink->imageUrl))
                                    {!! Form::file('imageUrl', ['id' => 'avatar' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('imageUrl', ['id' => 'avatar', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}
                                @endif

                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($masterquicklink->imageUrl))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
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
