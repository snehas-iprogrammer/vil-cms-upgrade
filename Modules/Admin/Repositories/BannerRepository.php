<?php
/**
 * The repository class for managing banner specific actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Banner;
use Modules\Admin\Models\BannerCategory;
use Modules\Admin\Services\Helper\ImageHelper;
use Illuminate\Support\Facades\Redis;
use Exception;
use Route;
use Auth;
use Log;
use Cache;
use URL;
use File;
use DB;
use PDO;

class BannerRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(Banner $model)
    {
        $this->model = $model;
    }

    /**
     * Get a listing of the resource.
     *
     * @return Response
     */
    public function data($params = [])
    {
        Cache::tags(Banner::table())->flush();
        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(Banner::table())->remember($cacheKey, $this->ttlCache, function() {
            return Banner::orderBy('updated_at', 'desc')->get();
        });

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Form data posted from ajax $inputs
     * @return $result array with status and message elements
     */
    public function create($inputs)
    {
        try {
            $filteredArray = array_unique(array_filter($inputs['circle']));
            //Redis::flushDB(); // delete all keys
            if(isset($inputs['banner_screen'])){
                if($inputs['banner_screen'] == 'GiftRecharge' || $inputs['banner_screen'] == 'HeroUnlimitedPacks' || $inputs['banner_screen'] == 'SonyLivPacks' ||$inputs['banner_screen'] == 'DisneyHotstarPacks' ){
                    if(Redis::keys($inputs['banner_screen'].'_*') != null){  Redis::del(Redis::keys($inputs['banner_screen'].'_*')); }
                }else{
                    if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                    if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                    if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                    if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                    if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                    if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                    if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                    if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                    if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                    if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
                }
            }else{
                if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
            }
            $inputs['circle'] = implode(',', $filteredArray);
            
            if(isset($inputs['service_type'])){
                $filteredServiceTypeArray = array_unique(array_filter($inputs['service_type']));
                $inputs['service_type'] = implode(',', $filteredServiceTypeArray);
            }

            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);

            /*if(isset($inputs['socid']) && $inputs['socid'] != '' ){
                $filteredSocidArray = array_unique(array_filter($inputs['socid']));
                $inputs['socid'] = implode(',', $filteredSocidArray);
            }else{
                $inputs['socid'] = '';
            }*/

            // if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){
            //     $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
            //     $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
            // }else{
            //     $inputs['postpaid_persona'] = '';
            // }

            // if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){
            //     $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
            //     $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
            // }else{
            //     $inputs['prepaid_persona'] = '';
            // }

            if(isset($inputs['red_hierarchy']) && $inputs['red_hierarchy'] != '' ){
                $red_hierarchy = array_unique(array_filter($inputs['red_hierarchy']));
                $inputs['red_hierarchy'] = implode(',', $red_hierarchy);
            }else{
                $inputs['red_hierarchy'] = '';
            }

            if(isset($inputs['lob']) && $inputs['lob'] != 'Both'){
                if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){
                    $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                    $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
                }else{
                    $inputs['postpaid_persona'] = '';
                }
                if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){
                    $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                    $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
                }else{
                    $inputs['prepaid_persona'] = '';
                }
            }else{
                $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
                $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
            }
            
            $banner = new $this->model;
            $allColumns = $banner->getTableColumns($banner->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $banner->banner_name = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $banner->$key = $value;
                    }
                }    
            }
            
            if($inputs['start_date_time'] != ''){ $banner->start_date_time = $inputs['start_date_time']; } 
            if($inputs['end_date_time'] != ''){ $banner->end_date_time = $inputs['end_date_time']; }
            if($inputs['banner_text_content'] != ''){ $banner->banner_text_content = $inputs['banner_text_content']; }
            if(isset($inputs['plan']) && $inputs['plan'] != ''){ $banner->plan = $inputs['plan']; }else{ $banner->plan = NULL; }
            if(isset($inputs['internal_link']) && $inputs['internal_link'] != '' ){ $banner->external_link = '';$banner->package_name = ''; }
            if(isset($inputs['external_link']) && $inputs['external_link'] != '' ){ $banner->internal_link = ''; }
        //    if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $banner->postpaid_persona = '';$banner->socid ='';}
         //   if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $banner->prepaid_persona = ''; }
            
            $banner->updated_by = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $save = $banner->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $banner);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Banner']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Banner']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
    
    /**
     * Update Offer category icon.
     *
     * @param  array  $inputs
     * @param  Modules\Admin\Models\OfferCategory $testimonial
     * @return void
     */
    public function updateCategoryIcon($inputs, $banner) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            // if (!empty($inputs['previous_image'])) {
            //     File::Delete(public_path() . ImageHelper::getBannerUploadFolder($banner->id) . $inputs['previous_image']);
            // }
            if (isset($inputs['previous_image'])) {
                $data = [];
                $data['banner_name'] = $inputs['previous_image'];
                DB::table('banner_history')->insert($data);
              //  $delete = ImageHelper::deleteS3File($inputs['previous_image']);
            }

            //$banner->banner_name = ImageHelper::uploadBanner($inputs['image'], $banner);
            $banner->banner_name = ImageHelper::uploadBannerS3($inputs['image'], $banner);

            $banner->save();
        } else if ($inputs['remove'] == 'remove') {
            $banner->banner_name = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getBannerUploadFolder($banner->id) . $inputs['previous_image']);
            }
            $banner->save();
        } else {
            $banner->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $banner)
    {
        try {
          // echo "<pre>";print_r($inputs);die;
            $filteredArray = array_unique(array_filter($inputs['circle']));
            //Redis::flushDB();
            if(isset($inputs['banner_screen'])){
                if($inputs['banner_screen'] == 'GiftRecharge' || $inputs['banner_screen'] == 'HeroUnlimitedPacks' || $inputs['banner_screen'] == 'SonyLivPacks' ||$inputs['banner_screen'] == 'DisneyHotstarPacks' ){
                    if(Redis::keys($inputs['banner_screen'].'_*') != null){  Redis::del(Redis::keys($inputs['banner_screen'].'_*')); }
                }else{
                    if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                    if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                    if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                    if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                    if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                    if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                    if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                    if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                    if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                    if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
                    // if(Redis::keys('DisneyHotstarPacks_*') != null){  Redis::del(Redis::keys('DisneyHotstarPacks_*')); }
                    // if(Redis::keys('HeroUnlimitedPacks_*') != null){  Redis::del(Redis::keys('HeroUnlimitedPacks_*')); }
                    // if(Redis::keys('SonyLivPacks_*') != null){  Redis::del(Redis::keys('SonyLivPacks_*')); }
                    // if(Redis::keys('GiftRecharge_*') != null){  Redis::del(Redis::keys('GiftRecharge_*')); }

                }
            }else{
                if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
            }


            $inputs['circle'] = implode(',', $filteredArray);
            
            if(isset($inputs['service_type'])){
                $filteredServiceTypeArray = array_unique(array_filter($inputs['service_type']));
                $inputs['service_type'] = implode(',', $filteredServiceTypeArray);
            }
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            /*if(isset($inputs['socid']) && $inputs['socid'] != '' ){
                $filteredSocidArray = array_unique(array_filter($inputs['socid']));
                $inputs['socid'] = implode(',', $filteredSocidArray);
            }else{
                $inputs['socid'] = '';
            }*/
            if(isset($inputs['lob']) && $inputs['lob'] != 'Both'){
                if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){
                    $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                    $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
                }else{
                    $inputs['postpaid_persona'] = '';
                }
                if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){
                    $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                    $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
                }else{
                    $inputs['prepaid_persona'] = '';
                }
            }else{
                $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
                $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
            } 

            if(isset($inputs['red_hierarchy']) && $inputs['red_hierarchy'] != '' ){
                $red_hierarchy = array_unique(array_filter($inputs['red_hierarchy']));
                $inputs['red_hierarchy'] = implode(',', $red_hierarchy);
            }else{
                $inputs['red_hierarchy'] = '';
            }

            foreach ($inputs as $key => $value) {
                if (isset($banner->$key)) {
                    $banner->$key = $value;
                }else{
                    if($key == 'recommended_offer_plans' && $inputs['recommended_offer_plans'] != null){
                        $banner->recommended_offer_plans = $value;
                    }
                    
                    if($key == 'login_type' && $inputs['login_type'] != null){
                        $banner->login_type = $value;
                    }
                }
            }
           
            if($inputs['start_date_time'] != ''){ $banner->start_date_time = $inputs['start_date_time']; } 
            if($inputs['end_date_time'] != ''){ $banner->end_date_time = $inputs['end_date_time']; }
            if($inputs['banner_text_content'] != ''){ $banner->banner_text_content = $inputs['banner_text_content']; }
            if(isset($inputs['plan']) && $inputs['plan'] != ''){ $banner->plan = $inputs['plan']; }else{ $banner->plan = NULL; }
            
            if(isset($inputs['internal_link']) && $inputs['internal_link'] != '' ){ $banner->external_link = '';$banner->package_name = ''; }
            if(isset($inputs['external_link']) && $inputs['external_link'] != '' ){ $banner->internal_link = ''; }
            //if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $banner->postpaid_persona = '';$banner->socid ='';}
            //if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $banner->prepaid_persona = ''; }
          //  if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $banner->prepaid_persona = 'All';$banner->postpaid_persona = 'All';$banner->socid ='All SOCID'; }
            $banner->updated_by = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $save = $banner->save();
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $banner);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Banner']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Banner could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Delete actions on banners
     *
     * @param  int  $inputs
     * @return int
     */
    public function deleteAction($inputs)
    {
        try {
                        $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
           
            switch ($action) {
                case "update":
                    $bannerIds = explode(',', $inputs['ids']);
                    foreach ($bannerIds as $key => $bannerId) {
                        $bannerDetails = Banner::find($bannerId);
                        if($bannerDetails['banner_screen'] == 'GiftRecharge' || $bannerDetails['banner_screen'] == 'HeroUnlimitedPacks' || $bannerDetails['banner_screen'] == 'SonyLivPacks' ||$bannerDetails['banner_screen'] == 'DisneyHotstarPacks' ){
                            if(Redis::keys($bannerDetails['banner_screen'].'_*') != null){  Redis::del(Redis::keys($bannerDetails['banner_screen'].'_*')); }
                        }else{
                            if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                            if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                            if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                            if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                            if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                            if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                            if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                            if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                            if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                            if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
                        }
                        if (!empty($bannerDetails)) {
                            switch ($inputs['value']) {
                                case '1': $bannerDetails->status = 1;
                                    break;
                                case '0': $bannerDetails->status = 0;
                                    break;
                            }
                            $bannerDetails->save();
                            $resultStatus = true;
                        }
                    }
                    break;
                case "delete":
                    $bannerIds = explode(',', $inputs['ids']);
                    foreach ($bannerIds as $key => $bannerId) {
                        $bannerDetails = Banner::find($bannerId);
                        if($bannerDetails['banner_screen'] == 'GiftRecharge' || $bannerDetails['banner_screen'] == 'HeroUnlimitedPacks' || $bannerDetails['banner_screen'] == 'SonyLivPacks' ||$bannerDetails['banner_screen'] == 'DisneyHotstarPacks' ){
                            if(Redis::keys($bannerDetails['banner_screen'].'_*') != null){  Redis::del(Redis::keys($bannerDetails['banner_screen'].'_*')); }
                        }else{
                            if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                            if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                            if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                            if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                            if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                            if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                            if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                            if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                            if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                            if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
                        }
                        if (!empty($bannerDetails)) {
                            $delete = ImageHelper::deleteS3File($bannerDetails['banner_name']);
                            $bannerDetails->delete();
                        }
                        $resultStatus = true;
                    }
                    $update = Banner::orderBy('id', 'DESC')->first();
                    if(!empty($update)){
                        $update['updated_at'] = date("Y-m-d H:i:s");
                        $result = $update->save();
                    }
                    break;
                case "copy":
                    $bannerIds = explode(',', $inputs['ids']);
                    foreach ($bannerIds as $key => $bannerId) {
                        $bannerDetails = Banner::find($bannerId);
                        if($bannerDetails['banner_screen'] == 'GiftRecharge' || $bannerDetails['banner_screen'] == 'HeroUnlimitedPacks' || $bannerDetails['banner_screen'] == 'SonyLivPacks' ||$bannerDetails['banner_screen'] == 'DisneyHotstarPacks' ){
                            if(Redis::keys($bannerDetails['banner_screen'].'_*') != null){  Redis::del(Redis::keys($bannerDetails['banner_screen'].'_*')); }
                        }else{
                            if(Redis::keys('banners_*') != null){  Redis::del(Redis::keys('banners_*')); } // delete only pattern match to banners_
                            if(Redis::keys('dashboard_upper_banners_*') != null){  Redis::del(Redis::keys('dashboard_upper_banners_*')); } 
                            if(Redis::keys('dashboard_center_banners_*') != null){  Redis::del(Redis::keys('dashboard_center_banners_*')); }
                            if(Redis::keys('downtime_banners_*') != null){  Redis::del(Redis::keys('downtime_banners_*')); }
                            if(Redis::keys('full_page_banners_*') != null){  Redis::del(Redis::keys('full_page_banners_*')); }
                            if(Redis::keys('exclusive_offer_banners_*') != null){  Redis::del(Redis::keys('exclusive_offer_banners_*')); }
                            if(Redis::keys('cashback_offer_banners_*') != null){  Redis::del(Redis::keys('cashback_offer_banners_*')); }
                            if(Redis::keys('vi_rise_banners_*') != null){  Redis::del(Redis::keys('vi_rise_banners_*')); }
                            if(Redis::keys('coupon_banners*') != null){  Redis::del(Redis::keys('coupon_banners*')); }
                            if(Redis::keys('spin_wheel_banners_*') != null){  Redis::del(Redis::keys('spin_wheel_banners_*')); }
                        }
                        if (!empty($bannerDetails)) {
                            $bannerDetails['status'] = 0;
                            $bannerDetails['banner_name'] = '';
                            $newBanner = $bannerDetails->replicate()->save();                            
                        }
                        $resultStatus = true;
                    }
                    break;   
                
                default:
                    break;
            }

            if ($resultStatus) {
                $action = (!empty($inputs['action'])) ? $inputs['action'] : 'update';
                switch ($action) {
                    case 'delete' :
                        $message = trans('admin::messages.deleted', ['name' => trans('admin::messages.banner(s)')]);
                        break;

                    case 'copy' :
                        $message = trans('admin::messages.copied', ['name' => trans('admin::messages.banner(s)')]);
                        break;
                    case 'update' :
                            $message = trans('admin::messages.updated', ['name' => trans('admin::messages.banner(s)')]);
                            break;
                    default:
                        $message = trans('admin::messages.group-action-success');
                }
                $response = ['status' => 'success', 'message' => $message];
            } else {
                $response = ['status' => 'fail', 'message' => trans('admin::messages.error-update')];
            }

            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Banner could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
