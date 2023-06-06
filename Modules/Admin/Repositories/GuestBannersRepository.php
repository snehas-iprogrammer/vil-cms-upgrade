<?php
/**
 * The repository class for managing AppVersion actions.
 *
 *
 * @author Sneha Shete <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\GuestBanners;
use Illuminate\Support\Facades\Redis;
use Modules\Admin\Services\Helper\ImageHelper;
use Exception;
use Route;
use Log;
use Cache;
use URL;
use File;
use DB;
use PDO;

class GuestBannersRepository extends BaseRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  Modules\Admin\Models\GuestBanners $GuestBanners
     * @return void
     */
    public function __construct(GuestBanners $guestbanner)
    {
        $this->model = $guestbanner;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function data($params = [])
    {
       // echo "<pre>*******************";print_r($params);die;
        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(GuestBanners::table())->remember($cacheKey, $this->ttlCache, function() {
            return GuestBanners::orderBy('updated_at', 'desc')->get();
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
        
            if(Redis::keys('guest_banners_*') != null){  Redis::del(Redis::keys('guest_banners_*')); } // delete only pattern match to banners_
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            $guestbanner = new $this->model;
            $allColumns = $guestbanner->getTableColumns($guestbanner->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $guestbanner->banner_image = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $guestbanner->$key = $value;
                    }
                }    
            }
            $save = $guestbanner->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $guestbanner);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Guest Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Guest Banner']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Guest Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Guest Banner']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $guestbanner) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            // if (!empty($inputs['previous_image'])) {
            //     File::Delete(public_path() . ImageHelper::getBannerUploadFolder($guestbanner->id) . $inputs['previous_image']);
            // }
            if (isset($inputs['previous_image'])) {
                $delete = ImageHelper::deleteS3File($inputs['previous_image']);
            }

            $guestbanner->banner_image = ImageHelper::uploadBannerS3($inputs['image'], $guestbanner);

            $guestbanner->save();

            
        } else if ($inputs['remove'] == 'remove') {
            $guestbanner->banner_image = ''; 
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getBannerUploadFolder($guestbanner->id) . $inputs['previous_image']);
            }
            $guestbanner->save();
        } else {
            $guestbanner->save();
        }
        return true;
    }

    /**
     * Update an faq category.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\FaqCategory $faqCategory
     * @return $result array with status and message elements
     * @return void
     */
    public function update($inputs, $guestbanner)
    {
        try {
            if(Redis::keys('guest_banners_*') != null){  Redis::del(Redis::keys('guest_banners_*')); } // delete only pattern match to banners_
           
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            foreach ($inputs as $key => $value) {
                if (isset($guestbanner->$key)) {
                    $guestbanner->$key = $value;
                }else{
                    
                }
            }
            
            $save = $guestbanner->save();
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $guestbanner);

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
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Guest Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Guest Banner could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Delete actions on faq categories
     *
     * @param  int  $status
     * @return int
     */
    public function deleteAction($inputs)
    {
        try {

            // $resultStatus = false;
            // $id = $inputs['ids'];
            // $guestbanner = GuestBanners::find($id);
            // if (!empty($guestbanner)) {            
            //     Redis::flushDB();
            //     $guestbanner->delete();
            //     $resultStatus = true;
            // }

            if(Redis::keys('guest_banners_*') != null){  Redis::del(Redis::keys('guest_banners_*')); }
            $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
            
            switch ($action) {
                case "update":
                    $bannerIds = explode(',', $inputs['ids']);
                    foreach ($bannerIds as $key => $bannerId) {
                        $bannerDetails = GuestBanners::find($bannerId);
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
                        $bannerDetails = GuestBanners::find($bannerId);
                        if (!empty($bannerDetails)) {
                            $delete = ImageHelper::deleteS3File($bannerDetails['banner_image']);
                            $bannerDetails->delete();
                        }
                        $resultStatus = true;
                    }
                    break;
                case "copy":
                    $bannerIds = explode(',', $inputs['ids']);
                    foreach ($bannerIds as $key => $bannerId) {
                        $bannerDetails = GuestBanners::find($bannerId);
                        if (!empty($bannerDetails)) {
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
                        $message = trans('admin::messages.deleted', ['name' => "Guest banner(s)"]);
                        break;

                    case 'copy' :
                        $message = trans('admin::messages.copied', ['name' => "Guest banner(s)"]);
                        break;
                    case 'update' :
                        $message = trans('admin::messages.updated', ['name' => "Guest banner(s)"]);
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
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'App version']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'App version']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    
}
