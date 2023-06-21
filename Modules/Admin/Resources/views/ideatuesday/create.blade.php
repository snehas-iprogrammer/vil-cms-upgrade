@section('page-level-styles')
@parent
<style type="text/css">
    .ajax-loader { visibility: hidden; background-color: rgba(255,255,255,0.7);position: fixed;z-index: +500 !important;width: 100%;height:100%;}
    .ajax-loader img {position: relative;top:25%;left:30%;} 
}
</style>
@stop

<div class="portlet box blue add-form-main">
    <div class="ajax-loader">
            <img src="{{ URL::asset('images/uploading.gif') }}" class="img-responsive" />
        </div>
    <div class="portlet-title togglelable">
        <div class="caption">
            <i class="fa fa-plus"></i>Upload IdeaTuesday Images
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse box-expand-form"></a>
        </div>
    </div>
    <div class="portlet-body form">
        {!! Form::open(['route' => ['ideatuesday.store'], 'method' => 'post', 'files' => 'true', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal ideatuesday-form',  'id' => 'create-ideatuesday', 'msg' => trans('admin::messages.added',['name'=>trans('admin::controller/banner.banner')])]) !!}
        @include('admin::ideatuesday.form',['action'=>'create'])
        <div class="form-actions">
            <div class="col-md-6">
                <div class="col-md-offset-6 col-md-9">
                    <button type="submit" class="btn green">{!! trans('admin::messages.submit') !!}</button>
                    <button type="button" class="btn default btn-collapse btn-collapse-form">{!! trans('admin::messages.cancel') !!}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
