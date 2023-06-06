<div class="portlet box yellow-gold edit-form-main">
    <div class="portlet-title togglelable">
        <div class="caption">
            <i class="fa fa-pencil"></i>{!! trans('admin::messages.edit-name', ['name' => trans('gallery') ]) !!}
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse box-expand-form"></a>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="ajax-loader-edit">
            <img src="{{ URL::asset('images/loader.gif') }}" class="img-responsive" />
        </div>
        {!! Form::model($gallery, ['route' => ['admin.gallery.update', $gallery->id], 'method' => 'put', 'class' => 'form-horizontal panel testimonials-form','id'=>'edit-gallery', 'msg' => trans('admin::messages.updated',['name'=>trans('gallery')]) ]) !!}
        @include('admin::gallery.form',['action'=>'update'])
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-offset-4 col-md-9">
                        <button type="submit" class="btn green">{!! trans('admin::messages.save') !!}</button>
                        <button type="button" class="btn default btn-collapse btn-collapse-form-edit">{!! trans('admin::messages.cancel') !!}</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>