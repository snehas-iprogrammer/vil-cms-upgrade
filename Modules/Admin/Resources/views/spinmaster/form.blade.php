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
                <label class="col-md-4 control-label">Slot Name<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                {!! Form::text('slot_name', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter slot name.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="col-md-4 control-label">Reward Type<span class="required" aria-required="true">*</span></label>
                    <div class="col-md-8">
                        {!! Form::select('reward_type', [''=> trans('admin::messages.select', [ 'name' => 'reward_type' ])] + $rewardList , null,['class'=>'select2me form-control',  'data-rule-maxlength' => '255', 'data-rule-required'=>'true','id' => 'reward_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select reward type.']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Details</label>
                <div class="col-md-8">
                    {!! Form::text('detail', null, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>  
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Coupon Code</label>
                <div class="col-md-8">
                    {!! Form::text('coupon_code', null, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>  
    </div> 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Title<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('title', null, ['class'=>'form-control', 'data-rule-maxlength' => '255', 'data-rule-required'=>'true', 'data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>  
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Subtitle</label>
                <div class="col-md-8">
                    {!! Form::text('sub_title', null, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>  
    </div>  
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Description<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::textarea('description', null, ['rows' => 4, 'class'=>'form-control', 'id'=>'description'])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">   
            <div class="form-group">
                <label class="col-md-4 control-label">Expiry Date</label>
                <div class="col-md-8 date form_datetime">
                    {!! Form::text('expiry_date', null, ['class'=>'form-control','style' => 'width: 258px; display: inline;','id'=>'expiry_date','placeholder'=>'expiry_date ']) !!}
                    <span class="input-group-btn" style="display: -webkit-inline-box; vertical-align: bottom; ">
                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                        <button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
                    </span>
                </div>
            </div> 
            <div class="form-group">
                <label class="col-md-4 control-label">Link Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    @if($action == 'update')
                        <?php
                            $linkType = '';
                            if($SpinMaster->internal_link != ''){ $linkType = 1; }
                            if($SpinMaster->external_link != ''){ $linkType = 2; }
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.internal-link') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('internal_link', null, ['class'=>'form-control', 'id'=>'internal_link','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.external-link') !!}</label>
                <div class="col-md-8">
                    {!! Form::text('external_link', null, ['class'=>'form-control', 'id'=>'external_link', 'data-rule-maxlength' => '255']) !!}
                    <span class="help-block">Enter external link Eg. http://myvi.mydala.com</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Merchant Name</label>
                <div class="col-md-8">
                    {!! Form::text('merchant_name', null, ['class'=>'form-control', 'id'=>'merchant_name','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label col-md-4">Logo Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($SpinMaster->logo_image))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getBannerImagePath($SpinMaster->id, $SpinMaster->logo_image) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-img.png'), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image_logo_image', $SpinMaster->logo_image, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($SpinMaster->logo_image))
                                    {!! trans('admin::controller/banner.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/banner.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($SpinMaster->logo_image))
                                    {!! Form::file('image', ['id' => 'avatar' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('image', ['id' => 'avatar', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}
                                @endif

                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($SpinMaster->logo_image))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label col-md-4">Overlay Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($SpinMaster->overlay_image))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getBannerImagePath($SpinMaster->id, $SpinMaster->overlay_image) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-img.png'), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image_overlay_image', $SpinMaster->overlay_image, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($SpinMaster->overlay_image))
                                    {!! trans('admin::controller/banner.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/banner.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($SpinMaster->overlay_image))
                                    {!! Form::file('overlay_image', ['id' => 'avatar2' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('overlay_image', ['id' => 'avatar2', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => 'overlay_image' ]) ,'class' => 'field']) !!}
                                @endif

                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($SpinMaster->overlay_image))
                               
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
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.banner-rank') !!}<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('reward_rank', [''=> trans('admin::messages.select-banner-rank', [ 'name' => trans('admin::controller/SpinMaster.select-SpinMaster-rank') ])] + $rankList, null,['class'=>'select2me form-control', 'id' => 'reward_rank', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select SpinMaster rank.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Benefit ID</label>
                <div class="col-md-8">
                    {!! Form::text('benefit_id', null, ['class'=>'form-control', 'id'=>'benefit_id','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Is Big Price <span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    <div class="radio-list">
                        <label class="radio-inline">{!! Form::radio('is_big_prize', '1') !!} True</label>
                        <label class="radio-inline">{!! Form::radio('is_big_prize', '0',true) !!} False</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Quantity</label>
                <div class="col-md-8">
                    {!! Form::text('quantity', null, ['class'=>'form-control', 'id'=>'quantity','data-rule-maxlength' => '255']) !!}
                </div>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-4 control-label">Prize Count</label>
                    <div class="col-md-8">
                        {!! Form::text('prize_count', null, ['class'=>'form-control', 'id'=>'prize_count','data-rule-maxlength' => '255']) !!}
                    </div>
                </div>
        </div>
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
