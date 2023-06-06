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
                <label class="col-md-4 control-label">Screen ID<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::select('anon_screen_id', [''=>'Select Screen ID'] + $screenList, null,['class'=>'select2me form-control', 'id' => 'anon_screen_id', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => trans('admin::controller/state.country')])]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Title<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('title', null, ['minlength'=>2, 'id'=>'title','class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Title.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Title']) ])!!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Description<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::textarea('description', null, ['minlength'=>3, 'rows' => 4, 'class'=>'form-control', 'id'=>'description', 'data-rule-required'=>'true', 'data-msg-required'=>trans('admin::messages.required-enter', ['name' => 'Description']), 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Description']) ])!!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-4">Media Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::select('media_type', $mediaTypeList , null,['class'=>'select2me form-control', 'id' => 'media_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Media Type.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-4">Media Shape<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::select('shape', $shapeList , null,['class'=>'select2me form-control', 'id' => 'shape', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select Shape.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group ">
                <label class="control-label col-md-4">Media File<span class="required" aria-required="true"></span></label>
                <div class="col-md-8 testimonial-image">
                    <p>{!! trans('admin::messages.mimes').' '.trans('Maximum Allowed Size 800kb') !!}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                            @if($action == 'update' && !empty($anonScreenCarouselDetails->media))
                            {!! \Modules\Admin\Services\Helper\ImageHelper::getAnonScreenCarouselDetailsImagePath($anonScreenCarouselDetails->id, $anonScreenCarouselDetails->media) !!}
                            @else
                            {!! HTML::image(URL::asset('images/default-img.png'), 'default-img', ['class' => 'img-thumbnail img-responsive']); !!}
                            @endif

                            @if($action == 'update')
                            {!! Form::hidden('previous_image', $anonScreenCarouselDetails->media, ['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    @if($action == 'update' && !empty($anonScreenCarouselDetails->media))
                                    {!! trans('admin::controller/banner.change-image') !!}
                                    @else
                                    {!! trans('admin::controller/banner.select-image') !!}
                                    @endif
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                @if(!empty($anonScreenCarouselDetails->media))
                                    {!! Form::file('image', ['id' => 'avatar' ,'class' => 'field']) !!}
                                @else
                                    {!! Form::file('image', ['id' => 'avatar', 'data-rule-required'=>'false', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Media') ]) ,'class' => 'field']) !!}
                                @endif

                            </span>
                            <span class="fileinput-new">&nbsp;
                                @if(!empty($anonScreenCarouselDetails->media))
                               
                                @endif
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" id="video_link">
                <label class="col-md-4 control-label">Video Link<span class="required" aria-required="true"></span></label>
                <div class="col-md-6">
                    @if($action == 'update' && $anonScreenCarouselDetails->media_type == 'Video')
                        {!! Form::text('video_url', $anonScreenCarouselDetails->media, ['minlength'=>2, 'id'=>'video_url','class'=>'form-control', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter Video Link.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Video Link']) ])!!}
                    @else
                        {!! Form::text('video_url', null, ['minlength'=>2, 'id'=>'video_url','class'=>'form-control', 'data-rule-required'=>'false', 'data-msg-required'=>'Please enter Video Link.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Video Link']) ])!!}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-4 control-label">Rank<span class="required" aria-required="true">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('rank', null, ['minlength'=>1, 'id'=>'rank','class'=>'form-control', 'data-rule-required'=>'true', 'data-msg-required'=>'Please enter Rank.', 'data-msg-minlength'=>trans('admin::messages.error-minlength', ['name' => 'Rank']) ])!!}
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
