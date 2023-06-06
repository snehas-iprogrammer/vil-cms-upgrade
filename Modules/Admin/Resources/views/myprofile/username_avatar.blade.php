{{--*/ $userinfo = \Modules\Admin\Services\Helper\UserInfoHelper::getAuthUserInfo() /*--}}
<div class="portlet light profile-sidebar-portlet">
    <div class="profile-userpic">
        @if(!empty($userinfo->avatar))
        <img src="{{ URL::asset('img/'.$userinfo->id.'/'.$userinfo->avatar) }}" class="img-responsive" alt="" />
        @else
        <img src="{{ URL::asset('images/default-user-icon-profile.png ') }}" class="img-responsive" alt="" />
        @endif
    </div>
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">{{ $userinfo->first_name }} ({{ $userinfo->username }})</div>
    </div>
    <br />
</div>