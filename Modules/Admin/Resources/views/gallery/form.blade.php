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
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Image Title<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('title', null, ['minlength'=>2,'maxlength'=>255,'class'=>'form-control', 'id'=>'title', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => trans('Image title')]), 'data-rule-maxlength'=>'255', 'data-msg-maxlength'=>trans('admin::messages.error-maxlength', ['name'=>trans('Image title')]) , 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => trans('Image title')]) ])!!}
                </div>
            </div>
        </div>
        <!--  Banner Image alt text -->
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Image Alt Text</label>
                <div class="col-md-8">
                    {!! Form::text('image_alt_text', null, ['class'=>'form-control', 'maxlength' => 100, 'minlength'=>3,'id'=>'image_alt_text', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Image Alt Text(English)']) ])!!}
                    <span class="help-block">Eg: Image wise name to identify the gallery image.</span>
                </div>
            </div>
        </div>
        <!--  Banner Image alt text -->
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label col-md-4">Thumbnail Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($gallery->thumbnail_image))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getGalleryImg($gallery->id, $gallery->thumbnail_image) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-user-icon-profile.png '), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image1', $gallery->thumbnail_image, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error1' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($gallery->thumbnail_image))
                                    {!! trans('admin::controller/blogs.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/blogs.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($gallery->thumbnail_image))
                                    {!! Form::file('thumbnail_image', ['id' => 'avatar1' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('thumbnail_image', ['id' => 'avatar1', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}
                                @endif
                                
                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($gallery->thumbnail_image))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
                    <div class="clearfix margin-top-15 margin-bottom-15">
                        <span class="label label-danger">{!! trans('admin::messages.note') !!} </span>
                        <span style="margin-left:10px;">{!! trans('admin::controller/blogs.support-image-help') !!}</span><br>
                    </div>
                </div>
            </div>
        </div>
<!--    </div>

    <div class="row">-->
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label col-md-4">Gallery Image<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($gallery->image))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getGalleryImg($gallery->id, $gallery->image) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-user-icon-profile.png '), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image', $gallery->image, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($gallery->image))
                                    {!! trans('admin::controller/blogs.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/blogs.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($gallery->image))
                                    {!! Form::file('image', ['id' => 'avatar' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('image', ['id' => 'avatar', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}
                                @endif
                                
                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($gallery->image))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
                    <div class="clearfix margin-top-15 margin-bottom-15">
                        <span class="label label-danger">{!! trans('admin::messages.note') !!} </span>
                        <span style="margin-left:10px;">{!! trans('admin::controller/blogs.support-image-help') !!}</span><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">{!! trans('Order') !!} <span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::text('order', null, ['minlength'=>1,'class'=>'form-control', 'id'=>'order','data-rule-max'=>'1000', 'data-rule-required'=>'true','data-rule-digits'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => trans('order')]), 'data-rule-minlength'=>'1', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => trans('order')]),  ])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">Status<span class="required" aria-required="true">*</span> </label>
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

