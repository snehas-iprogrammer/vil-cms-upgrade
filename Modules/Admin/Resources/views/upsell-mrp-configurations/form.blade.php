@section('global-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/uniform/css/uniform.default.min.css') ) !!}
@stop

@section('page-level-styles')
@parent
{!! HTML::style( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') ) !!}
{!! HTML::style( URL::asset('css/admin/admin-user.css') ) !!}

@stop

@section('page-level-scripts')
@parent
{!! HTML::script( URL::asset('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js') ) !!}
{!! HTML::script( URL::asset('global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ) !!}
@stop
<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.circle') !!}<span class="required" aria-required="true"></span></label>
                <div class="col-md-6">
                    {!! Form::select('circle[]', $circleList, $selectedCirclesArray,['multiple'=>'multiple','class'=>'select2me form-control circle', 'id' => 'circle', 'data-rule-required'=>'false', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'Circle'])]) !!}  
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('admin::controller/banner.appversion') !!}</label>
                <div class="col-md-8">
                    {!! Form::select('app_version[]', $appVersionList, $selectedAppVersionArray,['multiple'=>'multiple','class'=>'select2me form-control app_version', 'id' => 'app_version', 'data-msg-required'=>trans('admin::messages.required-select', ['name' => 'app_version'])]) !!}  
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Current MRP<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('current_mrp', null, ['minlength'=>2,'class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Current MRP.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Current MRP']) ])!!}
                    <span class="help-block">Enter Current MRP Eg. 499</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Upsell MRP<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('upsell_mrp', null, ['minlength'=>2,'class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Upsell MRP.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Upsell MRP']) ])!!}
                    <span class="help-block">Enter Upsell MRP Eg. 449</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-4">Categroy<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::select('category', $categoryList , null,['class'=>'select2me form-control', 'id' => 'category', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Category.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Bottom Padding<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('bottom_padding', null, ['minlength'=>1, 'maxlength'=>1,'class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Bottom Padding.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Bottom Padding']) , 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name' => 'Bottom Padding'])])!!}
                    <span class="help-block">Enter Bottom Padding Eg. Y or N</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Is Large Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('is_large', null, ['minlength'=>1, 'maxlength'=>1,'class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Is Large Image.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Is Large Image']) , 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name' => 'Is Large Image'])])!!}
                    <span class="help-block">Enter Is Large Image Eg. Y or N</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
        <div class="form-group">
                <label class="control-label col-md-4">Is Colored <span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    <div class="radio-list">
                        <label class="radio-inline">{!! Form::radio('is_colored', '1') !!} Colored</label>
                        <label class="radio-inline">{!! Form::radio('is_colored', '0',true) !!} Non-Colored</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group ">
                <label class="control-label col-md-4">Banner Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($upsellMrpConfigurations->image))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getUpsellMrpConfigurationImagePath($upsellMrpConfigurations->id, $upsellMrpConfigurations->image) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-img.png'), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image', $upsellMrpConfigurations->image, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($upsellMrpConfigurations->image))
                                    {!! trans('admin::controller/banner.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/banner.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($upsellMrpConfigurations->image))
                                    {!! Form::file('image', ['id' => 'avatar' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('image', ['id' => 'avatar', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}
                                @endif

                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($upsellMrpConfigurations->image))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
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
