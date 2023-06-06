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

use Modules\Admin\Models\SocialGamingBanner;
use Modules\Admin\Models\GameScreen;
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

class SocialGamingBannerRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(SocialGamingBanner $model)
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
        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));

        $GameScreen = GameScreen::table();    
        $response = Cache::tags(SocialGamingBanner::table(), GameScreen::table())->remember($cacheKey, $this->ttlCache, function() use($GameScreen) {
            return SocialGamingBanner::Join($GameScreen, 'social_gaming.banner_screen', '=', $GameScreen . '.id')
                    ->select(['social_gaming.*', 'game_screens.name as banner_screen','game_screens.id as screen_id'])
                    ->orderBy(SocialGamingBanner::table() . '.updated_at','desc')
                    ->get();
        });

        return $response;
    }

    public function listCategory($params = [])
    {
        $response = DB::select('select *  from category order by id ASC');
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
            if(Redis::keys('Livemusics_*') != null){  Redis::del(Redis::keys('Livemusics_*')); } 
	        if(Redis::keys('gameRailSequence_*') != null){  Redis::del(Redis::keys('gameRailSequence_*')); } 
            if(Redis::keys('upperGameBanner_*') != null){  Redis::del(Redis::keys('upperGameBanner_*')); }
            if(Redis::keys('featuredGameBanner_*') != null){  Redis::del(Redis::keys('featuredGameBanner_*')); } 
            if(Redis::keys('trendingNowGameBanner_*') != null){  Redis::del(Redis::keys('trendingNowGameBanner_*')); } 
            if(Redis::keys('socialGamingBanner_*') != null){  Redis::del(Redis::keys('socialGamingBanner_*')); } 
            if(Redis::keys('bottomGameBanner_*') != null){  Redis::del(Redis::keys('bottomGameBanner_*')); } 
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); } 
            if(Redis::keys('yourFavoriteBanner_*') != null){  Redis::del(Redis::keys('yourFavoriteBanner_*')); }
            if(Redis::keys('popularDisneyBanner_*') != null){  Redis::del(Redis::keys('popularDisneyBanner_*')); }
            if(Redis::keys('esportBanner_*') != null){  Redis::del(Redis::keys('esportBanner_*')); }
            if(Redis::keys('onMobileBanner_*') != null){  Redis::del(Redis::keys('onMobileBanner_*')); }
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            $socialbannerDetails = GameScreen::find($inputs['banner_screen']);
                        
            $inputs['circle'] = implode(',', $filteredArray);
        
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);

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
            
            $socialbanner = new $this->model;
            $allColumns = $socialbanner->getTableColumns($socialbanner->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $socialbanner->banner_name = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        if($key == 'banner_title'){
                            $socialbanner->$key = strtolower($value);
                        }else{
                            $socialbanner->$key = $value;
                        }
                    }
                }    
            }

            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $socialbanner->postpaid_persona = '';$socialbanner->socid ='';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $socialbanner->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $socialbanner->prepaid_persona = 'All';$socialbanner->postpaid_persona = 'All';$socialbanner->socid ='All SOCID'; }
           
            $save = $socialbanner->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $socialbanner);

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
    public function updateCategoryIcon($inputs, $socialbanner) {
       
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            if (!empty($inputs['previous_image'])) {
                $data = [];
                $data['banner_name'] = $inputs['previous_image'];
                DB::table('banner_history')->insert($data);
               // File::Delete(public_path() . ImageHelper::getSocialGamingBannerUploadFolder($socialbanner->id) . $inputs['previous_image']);
            }
            $socialbanner->banner_name = ImageHelper::uploadSocialGamingBannerS3($inputs['image'], $socialbanner);
            $socialbanner->save();
        } else if ($inputs['remove'] == 'remove') {
            $socialbanner->banner_name = '';
            if (!empty($inputs['previous_image'])) {
                $data = [];
                $data['banner_name'] = $inputs['previous_image'];
                DB::table('banner_history')->insert($data);
               // File::Delete(public_path() . ImageHelper::getSocialGamingBannerUploadFolder($socialbanner->id) . $inputs['previous_image']);
            }
            $socialbanner->save();
        } else {
            $socialbanner->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $socialbanner
     * @return $result array with status and message elements
     */
    public function update($inputs, $socialbanner)
    {
        try {
            if(Redis::keys('Livemusics_*') != null){  Redis::del(Redis::keys('Livemusics_*')); } 
	        if(Redis::keys('gameRailSequence_*') != null){  Redis::del(Redis::keys('gameRailSequence_*')); } 
            if(Redis::keys('upperGameBanner_*') != null){  Redis::del(Redis::keys('upperGameBanner_*')); }
            if(Redis::keys('featuredGameBanner_*') != null){  Redis::del(Redis::keys('featuredGameBanner_*')); } 
            if(Redis::keys('trendingNowGameBanner_*') != null){  Redis::del(Redis::keys('trendingNowGameBanner_*')); } 
            if(Redis::keys('socialGamingBanner_*') != null){  Redis::del(Redis::keys('socialGamingBanner_*')); } 
            if(Redis::keys('bottomGameBanner_*') != null){  Redis::del(Redis::keys('bottomGameBanner_*')); } 
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); } 
            if(Redis::keys('yourFavoriteBanner_*') != null){  Redis::del(Redis::keys('yourFavoriteBanner_*')); }
            if(Redis::keys('popularDisneyBanner_*') != null){  Redis::del(Redis::keys('popularDisneyBanner_*')); }
            if(Redis::keys('esportBanner_*') != null){  Redis::del(Redis::keys('esportBanner_*')); }
            if(Redis::keys('onMobileBanner_*') != null){  Redis::del(Redis::keys('onMobileBanner_*')); }

            $filteredArray = array_unique(array_filter($inputs['circle']));
            $inputs['circle'] = implode(',', $filteredArray);
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
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
            
            foreach ($inputs as $key => $value) {
                if (isset($socialbanner->$key)) {
                    if($key == 'banner_title'){
                        $socialbanner->$key = strtolower($value);
                    }else{
                        $socialbanner->$key = $value;
                    }
                }
            }
            
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $socialbanner->postpaid_persona = '';$socialbanner->socid ='';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $socialbanner->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $socialbanner->prepaid_persona = 'All';$socialbanner->postpaid_persona = 'All';$socialbanner->socid ='All SOCID'; }
            $save = $socialbanner->save();

           // echo "<pre>";print_r($socialbanner);die;

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $socialbanner);

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
            
            if(Redis::keys('Livemusics_*') != null){  Redis::del(Redis::keys('Livemusics_*')); } 
	        if(Redis::keys('gameRailSequence_*') != null){  Redis::del(Redis::keys('gameRailSequence_*')); } 
            if(Redis::keys('upperGameBanner_*') != null){  Redis::del(Redis::keys('upperGameBanner_*')); }
            if(Redis::keys('featuredGameBanner_*') != null){  Redis::del(Redis::keys('featuredGameBanner_*')); } 
            if(Redis::keys('trendingNowGameBanner_*') != null){  Redis::del(Redis::keys('trendingNowGameBanner_*')); } 
            if(Redis::keys('socialGamingBanner_*') != null){  Redis::del(Redis::keys('socialGamingBanner_*')); } 
            if(Redis::keys('bottomGameBanner_*') != null){  Redis::del(Redis::keys('bottomGameBanner_*')); } 
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); } 
            if(Redis::keys('yourFavoriteBanner_*') != null){  Redis::del(Redis::keys('yourFavoriteBanner_*')); }
            if(Redis::keys('popularDisneyBanner_*') != null){  Redis::del(Redis::keys('popularDisneyBanner_*')); }
            if(Redis::keys('esportBanner_*') != null){  Redis::del(Redis::keys('esportBanner_*')); }
            if(Redis::keys('onMobileBanner_*') != null){  Redis::del(Redis::keys('onMobileBanner_*')); }

            $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
           
            switch ($action) {
                case "update":
                    $socialbannerIds = explode(',', $inputs['ids']);
                    foreach ($socialbannerIds as $key => $socialbannerId) {
                        $socialbannerDetails = SocialGamingBanner::find($socialbannerId);
                        $gamedetails = GameScreen::find($socialbannerDetails['banner_screen']);
                        
                        if (!empty($socialbannerDetails)) {
                            switch ($inputs['value']) {
                                case '1': $socialbannerDetails->status = 1;
                                    break;
                                case '0': $socialbannerDetails->status = 0;
                                    break;
                            }
                            $socialbannerDetails->save();
                            $resultStatus = true;
                        }
                    }
                    break;
                case "delete":
                    $socialbannerIds = explode(',', $inputs['ids']);
                    foreach ($socialbannerIds as $key => $socialbannerId) {
                        $socialbannerDetails = SocialGamingBanner::find($socialbannerId);
                        
                        if (!empty($socialbannerDetails)) {
                           // $delete = ImageHelper::deleteS3File($socialbannerDetails['banner_name']);
                            $socialbannerDetails->delete();
                        }
                        $resultStatus = true;
                    }
                    $update = SocialGamingBanner::orderBy('id', 'DESC')->first();
                    if(!empty($update)){
                        $update['updated_at'] = date("Y-m-d H:i:s");
                        $result = $update->save();
                    }
                    break;
                case "copy":
                    $socialbannerIds = explode(',', $inputs['ids']);
                    foreach ($socialbannerIds as $key => $socialbannerId) {
                        $socialbannerDetails = SocialGamingBanner::find($socialbannerId);
                        
                        if (!empty($socialbannerDetails)) {
                            $socialbannerDetails['status'] = 0;
                            $socialbannerDetails['banner_name'] = '';
                            $newBanner = $socialbannerDetails->replicate()->save();                            
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