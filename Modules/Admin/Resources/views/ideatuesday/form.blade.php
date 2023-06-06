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
        <div class="col-md-8">
            <div class="form-group">
                <label class="col-md-4 control-label">Select Image Type<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8">
                    {!! Form::select('image_type', [''=> trans('admin::messages.select-image-type', [ 'name' => trans('admin::controller/banner.select-image-type') ])] + $imagesList , null,['class'=>'select2me form-control', 'id' => 'image_type', 'data-rule-required'=>'true', 'data-msg-required'=>'Please select image type.']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group ">
                <label class="control-label col-md-4">Upload Images<span class="required" aria-required="true">*</span></label>
                <div class="col-md-8 testimonial-image">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new testimonial-listing-img margin-bottom-10">
                            {!! Form::hidden('remove', '', ['class'=>'form-control', 'id'=>'remove']) !!}
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div id='file-error' class='text-danger margin-bottom-10 bold'></div>
                        <div class="inline">&nbsp;
                            <span class="btn default btn-file">
                                <span class="fileinput-new">
                                    Select Images
                                </span>
                                <span class="fileinput-exists">{!! trans('admin::messages.change') !!} </span>
                                    <!--{!! Form::file('image', ['id' => 'avatar', 'data-rule-required'=>'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Image') ]) ,'class' => 'field']) !!}-->
                                    {!! Form::file('images[]', ['id' => 'avatar','data-rule-required'=>'true', 'multiple'=> 'true', 'data-msg-required'=> trans('admin::messages.required-select', ['name' => trans('Images') ]) ,'class' => 'field']) !!}
                            </span>
                            <span class="fileinput-new">&nbsp;
                               
                            </span>&nbsp;
                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                {!! trans('admin::messages.remove') !!} </a>
                            
                            <p style="margin: 10px 0 10px !important;">{!! trans('admin::messages.mimes') !!} <br>
                               {!! trans('Maximum Allowed Size 800kb') !!}     <br>
                               {!! trans('For better performance, try to upload maximum <b>5</b> images at a time') !!}     
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
