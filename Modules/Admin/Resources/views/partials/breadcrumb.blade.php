@php

    $linkData = \Modules\Admin\Services\Helper\MenuHelper::getRouteByPage();
    if(!empty($linkData['page_header'])){
        $menus = [
        ['label' => $linkData['category_name'], 'link' => 'javascript:;'],
        ['label' => $linkData['link_name'], 'link' => '']];
    }
    
@endphp

@section('template-level-scripts')
@parent
<script>
    siteObjJs.admin.commonJs.constants.recordsPerPage = parseInt("{{--*/ echo $linkData['pagination'];/*--}}");
</script>
@stop

<div class="page-head">
    <div class="page-title">
        <h1>{!! $linkData['page_header'] !!}</h1>
        <input type="hidden" value="{{str_replace(' ', '_', $menus[0]['label'])}}" id="menu_name"/>
        <input type="hidden" value="{{str_replace(' ', '_', $linkData['link_name']).'_submenu'}}" id="submenu_name"/>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                {!! link_to('/admin/dashboard', trans('admin::controller/user.admin')) !!}<i class="fa fa-circle"></i> 
            </li>

            @foreach($menus as $menu)
            <li>
                @if(!empty($menu['link']) && $menu['link']=='javascript:;' )
                <a href="javascript:;">{{ $menu['label'] }}</a>
                @else
                <span class="text-muted"> {{ $menu['label'] }}</span>
                @endif
                <i class="fa fa-circle"></i> 
            </li>
            @endforeach
        </ul>

        @if($linkData['page_text'])
        <h4>
            {!! $linkData['page_text'] !!}
        </h4>
        @endif
    </div>
</div>