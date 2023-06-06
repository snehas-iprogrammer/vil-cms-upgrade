@php 

$sidebarLinks = \Modules\Admin\Services\Helper\MenuHelper::getSideBarLinks();
@endphp
<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
    <li class="text-center menuExpandCollapseBtn">
        <div class="btn-group btn-group-solid ">
            <button type="button" class="btn btn-xs blue menu-expand-all"><i class="fa fa-folder-open"></i>Expand All</button>
            <button type="button" class="btn btn-xs blue menu-collapse-all">Collapse All<i class="fa fa-folder"></i></button>
        </div>
    </li>
    <li class="start ">
        <a href="{!! URL::to('admin/dashboard') !!}" id="Dashboard">
            <i class="icon-home"></i><span class="title">Dashboard</span>
        </a>

    </li>
    
    @foreach ($sidebarLinks as $key=>$sidebarLinkMenu)
    <li class="heading">
        <h3 class="uppercase">{{ $sidebarLinkMenu['menu_group_name'] }}</h3>
    </li>
    @foreach ($sidebarLinkMenu['link_categories'] as $key=>$sidebarLink)
    <li id="{{str_replace(' ', '_', $sidebarLink[0]['category'])}}">
        <a href="javascript:;">
            <i class="{{ $sidebarLink[0]['category_icon'] }}"></i>
            <span class="title">{{ $sidebarLink[0]['category'] }}</span>
            <span class="arrow "></span>
        </a>
        <ul class="sub-menu">
            @foreach ($sidebarLink as $sidebarLinkItem)
            <li>
                @php
                if(Route::has($sidebarLinkItem['link_url'])) {
                    $link_href = route($sidebarLinkItem['link_url']);
                } else {
                    $link_href = '';
                }
                @endphp
                <a href="{{ $link_href }}" id="{{str_replace(' ', '_', $sidebarLinkItem['link_name']).'_submenu'}}"><i class="{{ $sidebarLinkItem['link_icon'] }}"></i>
                    <div class="menu-link">
                        {{ $sidebarLinkItem['link_name'] }}
                    </div>
                </a>

            </li>
            @endforeach
        </ul>
    </li>
    @endforeach
    @endforeach
</ul>