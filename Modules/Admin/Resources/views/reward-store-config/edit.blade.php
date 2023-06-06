<div class="portlet box yellow-gold edit-form-main">
    <div class="portlet-title togglelable">
        <div class="caption">
            <i class="fa fa-pencil"></i>{!! trans('admin::messages.edit-name', ['name' => trans('admin::controller/faq-category.faq-cat') ]) !!}
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse box-expand-form"></a>
        </div>
    </div>
    <div class="portlet-body form">
        {!! Form::model($rewardStoreConfig, ['route' => ['admin.reward-store-config.update', $rewardStoreConfig->id], 'method' => 'put', 'files' => 'true', 'class' => 'form-horizontal panel reward-store-config-form','id'=>'edit-reward-store-config', 'msg' => trans('admin::messages.updated',['name'=>'Reward Store Config']) ]) !!}
        @include('admin::reward-store-config.form')
        <div class="form-actions">
            <div class="col-md-6">
                <div class="col-md-offset-6 col-md-9">
                    <button type="submit" class="btn green">{!! trans('admin::messages.save') !!}</button>
                    <button type="button" class="btn default btn-collapse btn-collapse-form-edit">{!! trans('admin::messages.cancel') !!}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>