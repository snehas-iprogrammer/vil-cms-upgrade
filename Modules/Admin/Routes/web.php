<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::prefix('admin')->group(function() {
        Route::get('/', 'AdminController@index');
    });

    Route::group(['prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers', 'before' => 'auth'], function() {
    // Route::get('/', ['uses' => 'DashboardController@index', 'permission' => 'index']);
     Route::get('/dashboard', ['uses' => 'DashboardController@index', 'permission' => 'index']);

        
    // user management
    Route::get('user/data', ['as' => 'admin.user.apilist', 'uses' => 'UserController@getData', 'permission' => 'index']);
    Route::get('user/trashed', ['as' => 'admin.user.trashedlisting.index', 'uses' => 'UserController@trashed', 'permission' => 'index']);
    Route::get('user/trashed-data', ['as' => 'admin.user.apitrashedlist.index', 'uses' => 'UserController@getTrashedData', 'permission' => 'index']);
    Route::get('user/links', ['as' => 'admin.user.apiuserlinks.index', 'uses' => 'UserController@getUserLinks', 'permission' => 'index']);
    Route::post('user/group-action', ['as' => 'admin.user.groupaction', 'uses' => 'UserController@groupAction', 'permission' => 'update']);
    Route::post('user/check-avalability', ['as' => 'admin.user.checkfieldavalability.update', 'uses' => 'UserController@checkAvalability', 'permission' => 'update']);
    Route::resource('user', 'UserController');

// link category
    Route::get('link-category/data', ['as' => 'admin.link-category.apilist', 'uses' => 'LinkCategoryController@getData', 'permission' => 'index']);
    Route::post('link-category/group-action', ['as' => 'admin.link-category.groupaction', 'uses' => 'LinkCategoryController@groupAction', 'permission' => 'update']);
    Route::resource('link-category', 'LinkCategoryController');

// Permission Link Management
    Route::get('links/linkData/{lid}', ['as' => 'admin.links.linkList', 'uses' => 'LinksController@getLinksData', 'permission' => 'index']);
    Route::get('links/data', ['as' => 'admin.links.apilist', 'uses' => 'LinksController@getData', 'permission' => 'index']);
    Route::post('links/group-action', ['as' => 'admin.links.groupaction', 'uses' => 'LinksController@groupAction', 'permission' => 'update']);
    Route::resource('links', 'LinksController');

// Login Process
    Route::post('auth/authenticate', ['as' => 'admin.auth.authenticate', 'uses' => 'Auth\AuthController@authUsername', 'permission' => 'index']);

//manage ipadresses
    Route::get('ipaddress/data', ['as' => 'admin.ipaddress.apilist', 'uses' => 'IpAddressController@getData', 'permission' => 'index']);
    Route::post('ipaddress/group-action', ['as' => 'admin.ipaddress.groupaction', 'uses' => 'IpAddressController@groupAction', 'permission' => 'update']);
    Route::resource('ipaddress', 'IpAddressController');

// Configuration setting management
    Route::get('config-settings/data', ['as' => 'admin.config-settings.list', 'uses' => 'ConfigSettingController@getData', 'permission' => 'index']);
    Route::resource('config-settings', 'ConfigSettingController');

// Configuration Categories Management
    Route::get('config-categories/data', ['as' => 'admin.config-categories.list', 'uses' => 'ConfigCategoryController@getData', 'permission' => 'index']);
    Route::resource('config-categories', 'ConfigCategoryController');

// User Types Management
    Route::get('user-type/data', ['as' => 'admin.user-type.list', 'uses' => 'UserTypeController@getData', 'permission' => 'index']);
    Route::resource('user-type', 'UserTypeController');

// System emails
    Route::resource('system-emails', 'SystemEmailController');

// Pages Management
    Route::get('manage-pages/data', ['as' => 'admin.manage-pages.apilist', 'uses' => 'ManagePagesController@getData', 'permission' => 'index']);
    Route::post('manage-pages/group-action', ['as' => 'admin.manage-pages.groupaction', 'uses' => 'ManagePagesController@groupAction', 'permission' => 'update']);
    Route::resource('manage-pages', 'ManagePagesController');

// User Type Links
    Route::resource('usertype-links', 'UserTypeLinksController');

//manage faq category

    Route::get('faq-categories/data', ['as' => 'admin.faq-categories.list', 'uses' => 'FaqCategoryController@getData', 'permission' => 'index']);
    Route::resource('faq-categories', 'FaqCategoryController');

//manage faq
    Route::get('faqs/data', ['as' => 'admin.faqs.list', 'uses' => 'FaqController@getData', 'permission' => 'index']);
    Route::post('faqs/group-action', ['as' => 'admin.faqs.groupaction', 'uses' => 'FaqController@groupAction', 'permission' => 'update']);
    Route::resource('faqs', 'FaqController');

//admin myprofile
    Route::put('myprofile/update-avatar', ['as' => 'admin.myprofile.update-avatar', 'uses' => 'MyProfileController@updateAvatar', 'permission' => 'update']);
    Route::put('myprofile/change-password', ['as' => 'admin.myprofile.change-password', 'uses' => 'MyProfileController@changePassword', 'permission' => 'update']);
    Route::resource('myprofile', 'MyProfileController');

//manage country category
    Route::get('countries/data', ['as' => 'admin.countries.list', 'uses' => 'CountryController@getData', 'permission' => 'index']);
    Route::resource('countries', 'CountryController');

//manage State
    Route::get('states/data', ['as' => 'admin.states.list', 'uses' => 'StateController@getData', 'permission' => 'index']);
    Route::resource('states', 'StateController');

//manage cities category
    Route::get('cities/stateData/{cid}', ['as' => 'admin.cities.stateList', 'uses' => 'CityController@getStateData', 'permission' => 'index']);
    Route::get('cities/data', ['as' => 'admin.cities.list', 'uses' => 'CityController@getData', 'permission' => 'index']);
    Route::resource('cities', 'CityController');

//manage locations category
    Route::get('locations/stateData/{cid}', ['as' => 'admin.locations.stateList', 'uses' => 'LocationsController@getStateData', 'permission' => 'index']);
    Route::get('locations/cityData/{cid}', ['as' => 'admin.locations.cityList', 'uses' => 'LocationsController@getCityData', 'permission' => 'index']);
    Route::get('locations/data', ['as' => 'admin.locations.list', 'uses' => 'LocationsController@getData', 'permission' => 'index']);
    Route::resource('locations', 'LocationsController');


//file management
    Route::get('filemanager/show', ['as' => 'admin.filemanager.show', 'uses' => 'FilemanagerLaravelController@getShow']);
    Route::get('filemanager/connectors', ['as' => 'admin.filemanager', 'uses' => 'FilemanagerLaravelController@getConnectors']);
    Route::post('filemanager/connectors', ['as' => 'admin.filemanager_post', 'uses' => 'FilemanagerLaravelController@postConnectors']);
    Route::resource('medias', 'MediasController');

// menu groups
    Route::get('menu-group/data', ['as' => 'admin.menu-group.apilist', 'uses' => 'MenuGroupController@getData', 'permission' => 'index']);
    Route::post('menu-group/group-action', ['as' => 'admin.menu-group.groupaction', 'uses' => 'MenuGroupController@groupAction', 'permission' => 'update']);
    Route::resource('menu-group', 'MenuGroupController');

// banner management
    Route::get('banner/data', ['as' => 'admin.banner.list', 'uses' => 'BannerController@getData', 'permission' => 'index']);
    Route::post('banner/group-action', ['as' => 'admin.banner.groupaction', 'uses' => 'BannerController@groupAction', 'permission' => 'update']);
    Route::resource('banner', 'BannerController');
// Idea tuesday management
    Route::get('ideatuesday/data', ['as' => 'admin.ideatuesday.list', 'uses' => 'IdeatuesdayController@getData', 'permission' => 'index']);
    Route::post('ideatuesday/group-action', ['as' => 'admin.ideatuesday.groupaction', 'uses' => 'IdeatuesdayController@groupAction', 'permission' => 'update']);
    Route::resource('ideatuesday', 'IdeatuesdayController');        
    
//Gallery Management
    Route::get('gallery/data', ['as' => 'admin.gallery.list', 'uses' => 'GalleryController@getData', 'permission' => 'index']);
    Route::resource('gallery', 'GalleryController');
    
    Route::resource('upload-images', 'UploadImagesController');
    
//manage app versions
    Route::get('app-versions/data', ['as' => 'admin.app-versions.list', 'uses' => 'AppVersionsController@getData', 'permission' => 'index']);
    Route::resource('app-versions', 'AppVersionsController');
//manage redx html
    Route::get('redx-html/data', ['as' => 'admin.redx-html.list', 'uses' => 'RedxHtmlController@getData', 'permission' => 'index']);
    Route::resource('redx-html', 'RedxHtmlController');
//manage recharge offers
    Route::get('recharge-offers/data', ['as' => 'admin.recharge-offers.list', 'uses' => 'RechargeOffersController@getData', 'permission' => 'index']);
    Route::resource('recharge-offers', 'RechargeOffersController');
    
// other banners management
    Route::get('other-banners/data', ['as' => 'admin.other-banners.list', 'uses' => 'OtherBannersController@getData', 'permission' => 'index']);
    Route::post('other-banners/group-action', ['as' => 'admin.other-banners.groupaction', 'uses' => 'OtherBannersController@groupAction', 'permission' => 'update']);
    Route::resource('other-banners', 'OtherBannersController');
    
//manage segment details
    Route::get('segment-details/data', ['as' => 'admin.segment-details.list', 'uses' => 'SegmentDetailsController@getData', 'permission' => 'index']);
    Route::resource('segment-details', 'SegmentDetailsController');
    
//manage quick recharge details
    Route::get('quick-recharge-details/data', ['as' => 'admin.quick-recharge-details.list', 'uses' => 'QuickRechargeDetailsController@getData', 'permission' => 'index']);
    Route::resource('quick-recharge-details', 'QuickRechargeDetailsController');

// payment banners management
    Route::get('payment-banners/data', ['as' => 'admin.payment-banners.list', 'uses' => 'PaymentBannersController@getData', 'permission' => 'index']);
    Route::post('payment-banners/group-action', ['as' => 'admin.payment-banners.groupaction', 'uses' => 'PaymentBannersController@groupAction', 'permission' => 'update']);
    Route::resource('payment-banners', 'PaymentBannersController');
    
// upsell mrp configurations management
    Route::get('upsell-mrp-configurations/data', ['as' => 'admin.upsell-mrp-configurations.list', 'uses' => 'UpsellMrpConfigurationsController@getData', 'permission' => 'index']);
    Route::post('upsell-mrp-configurations/group-action', ['as' => 'admin.upsell-mrp-configurations.groupaction_post', 'uses' => 'UpsellMrpConfigurationsController@groupAction', 'permission' => 'update']);
    Route::resource('upsell-mrp-configurations', 'UpsellMrpConfigurationsController');

//manage silent otas
    Route::get('silent-otas/data', ['as' => 'admin.silent-otas.list', 'uses' => 'SilentOtasController@getData', 'permission' => 'index']);
    Route::resource('silent-otas', 'SilentOtasController');

//manage quick links
    Route::get('quick-links/data', ['as' => 'admin.quick-links.list', 'uses' => 'QuickLinksController@getData', 'permission' => 'index']);
    Route::post('quick-links/group-action', ['as' => 'admin.quick-links.groupaction', 'uses' => 'QuickLinksController@groupAction', 'permission' => 'update']);
    Route::resource('quick-links', 'QuickLinksController');    
    
// dashboard banners management
    Route::get('dashboard-banners/data', ['as' => 'admin.dashboard-banners.list', 'uses' => 'DashboardBannersController@getData', 'permission' => 'index']);
    Route::post('dashboard-banners/group-action', ['as' => 'admin.upsell-mrp-configurations.groupaction', 'uses' => 'DashboardBannersController@groupAction', 'permission' => 'update']);
    Route::resource('dashboard-banners', 'DashboardBannersController');
    
//manage digital onboarding
    Route::get('digital-onboarding/data', ['as' => 'admin.digital-onboarding.list', 'uses' => 'DigitalOnboardingController@getData', 'permission' => 'index']);
    Route::resource('digital-onboarding', 'DigitalOnboardingController');
    
//manage anon screen details
    Route::get('anon-screen-details/data', ['as' => 'admin.anon-screen-details.list', 'uses' => 'AnonScreenDetailsController@getData', 'permission' => 'index']);
    Route::resource('anon-screen-details', 'AnonScreenDetailsController');    
    
//manage anon screen carousel details
    Route::get('anon-screen-carousel-details/data', ['as' => 'admin.anon-screen-carousel-details.list', 'uses' => 'AnonScreenCarouselDetailsController@getData', 'permission' => 'index']);
    Route::resource('anon-screen-carousel-details', 'AnonScreenCarouselDetailsController');    

//manage dashboard config
    Route::get('dashboard-config/data', ['as' => 'admin.dashboard-config.list', 'uses' => 'DashboardConfigController@getData', 'permission' => 'index']);
    Route::resource('dashboard-config', 'DashboardConfigController');  
    
//manage reward store config
    Route::get('reward-store-config/data', ['as' => 'admin.reward-store-config.list', 'uses' => 'RewardStoreConfigController@getData', 'permission' => 'index']);
    Route::resource('reward-store-config', 'RewardStoreConfigController');
    
//manage videos
    Route::get('videos/data', ['as' => 'admin.videos.list', 'uses' => 'VideosController@getData', 'permission' => 'index']);
    Route::resource('videos', 'VideosController');    
    
// masterquicklink management
Route::get('masterquicklink/data', ['as' => 'admin.masterquicklink.list', 'uses' => 'MasterQuickLinkController@getData', 'permission' => 'index']);
Route::post('masterquicklink/group-action', ['as' => 'admin.masterquicklink.groupaction', 'uses' => 'MasterQuickLinkController@groupAction', 'permission' => 'update']);
Route::resource('masterquicklink', 'MasterQuickLinkController');

//manage quick links
Route::get('appquick-links/data', ['as' => 'admin.appquick-links.list', 'uses' => 'AppQuickLinksController@getData', 'permission' => 'index']);
Route::post('appquick-links/group-action', ['as' => 'admin.appquick-links.groupaction', 'uses' => 'AppQuickLinksController@groupAction', 'permission' => 'update']);
Route::resource('appquick-links', 'AppQuickLinksController');    

// Guest banners management
Route::get('guestbanner/data', ['as' => 'admin.guestbanner.list', 'uses' => 'GuestBannersController@getData', 'permission' => 'index']);
Route::post('guestbanner/group-action', ['as' => 'admin.guestbanner.groupaction', 'uses' => 'GuestBannersController@groupAction', 'permission' => 'update']);
Route::resource('guestbanner', 'GuestBannersController');

//manage Tabs
    Route::get('tab/data', ['as' => 'admin.tab.list', 'uses' => 'TabController@getData', 'permission' => 'index']);
    Route::resource('tab', 'TabController');

//Manage Social Gaming Banner 
Route::get('socialgamingbanner/data', ['as' => 'admin.socialgamingbanner.list', 'uses' => 'SocialGamingBannerController@getData', 'permission' => 'index']);
Route::post('socialgamingbanner/group-action', ['as' => 'admin.socialgamingbanner.groupaction', 'uses' => 'SocialGamingBannerController@groupAction', 'permission' => 'update']);
Route::resource('socialgamingbanner', 'SocialGamingBannerController');

//Manage jobs and education
Route::get('jobs/data', ['as' => 'admin.jobs.list', 'uses' => 'JobsController@getData', 'permission' => 'index']);
Route::post('jobs/group-action', ['as' => 'admin.jobsbanner.groupaction', 'uses' => 'JobsController@groupAction', 'permission' => 'update']);
Route::resource('jobs', 'JobsController');

//Manage Game Screen 
Route::get('gamescreen/data', ['as' => 'admin.gamescreen.list', 'uses' => 'GameScreenController@getData', 'permission' => 'index']);
Route::post('gamescreen/group-action', ['as' => 'admin.gamescreen.groupaction', 'uses' => 'GameScreenController@groupAction', 'permission' => 'update']);
Route::resource('gamescreen', 'GameScreenController');

//Manage Live Music 
Route::get('livemusic/data', ['as' => 'admin.livemusic.list', 'uses' => 'LivemusicController@getData', 'permission' => 'index']);
Route::post('livemusic/group-action', ['as' => 'admin.livemusic.groupaction', 'uses' => 'LivemusicController@groupAction', 'permission' => 'update']);
Route::resource('livemusic', 'LivemusicController');

//Manage Spin Master 
Route::get('spinmaster/data', ['as' => 'admin.spinmaster.list', 'uses' => 'SpinMasterController@getData', 'permission' => 'index']);
Route::post('spinmaster/group-action', ['as' => 'admin.spinmaster.groupaction', 'uses' => 'SpinMasterController@groupAction', 'permission' => 'update']);
Route::resource('spinmaster', 'SpinMasterController');

//Manage Spin Master Question-answer
Route::get('spinmasterqueans/data', ['as' => 'admin.spinmasterqueans.list', 'uses' => 'SpinMasterQueAnsController@getData', 'permission' => 'index']);
Route::post('spinmasterqueans/group-action', ['as' => 'admin.spinmasterqueans.groupaction', 'uses' => 'SpinMasterQueAnsController@groupAction', 'permission' => 'update']);
Route::resource('spinmasterqueans', 'SpinMasterQueAnsController');

################ PLEASE WRITE YOUR ROUTES ABOVE THIS CODE ##################################
// Route::controllers([
//     'auth' => 'Auth\AuthController',
//     'password' => 'Auth\PasswordController',
// ]);

    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');
    Route::get('auth/confirm/{token}', 'Auth\AuthController@getConfirm');
});
