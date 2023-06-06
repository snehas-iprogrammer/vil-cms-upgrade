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

use Modules\Admin\Models\OtherBanners;
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

class OtherBannersRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(OtherBanners $model)
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
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(OtherBanners::table())->remember($cacheKey, $this->ttlCache, function() {
            return OtherBanners::orderBy('id')->get();
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
            $redisKeys = Redis::keys('other_banners_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $inputs['circle'] = implode(',', $filteredArray);

            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);

            /*if(isset($inputs['socid']) && $inputs['socid'] != '' ){
                $filteredSocidArray = array_unique(array_filter($inputs['socid']));
                $inputs['socid'] = implode(',', $filteredSocidArray);
            }else{
                $inputs['socid'] = '';
            }*/

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
            
            $otherBanners = new $this->model;
            $allColumns = $otherBanners->getTableColumns($otherBanners->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $otherBanners->banner_name = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $otherBanners->$key = $value;
                    }
                }    
            }
            
            if(isset($inputs['internal_link']) && $inputs['internal_link'] != '' ){ $otherBanners->external_link = ''; }
            if(isset($inputs['external_link']) && $inputs['external_link'] != '' ){ $otherBanners->internal_link = ''; }
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $otherBanners->postpaid_persona = '';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $otherBanners->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $otherBanners->prepaid_persona = 'All';$otherBanners->postpaid_persona = 'All'; }

            $save = $otherBanners->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $otherBanners);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Other Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Other Banner']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Other Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Other Banner']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $otherBanners) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            // if (!empty($inputs['previous_image'])) {
            //     File::Delete(public_path() . ImageHelper::getOtherBannerUploadFolder($otherBanners->id) . $inputs['previous_image']);
            // }
            if (isset($inputs['previous_image'])) {
                $delete = ImageHelper::deleteS3File($inputs['previous_image']);
            }
            $otherBanners->banner_name = ImageHelper::uploadOtherBannerS3($inputs['image'], $otherBanners);
            //$otherBanners->banner_name = ImageHelper::uploadOtherBanner($inputs['image'], $otherBanners);

            $otherBanners->save();
        } else if ($inputs['remove'] == 'remove') {
            $otherBanners->banner_name = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getOtherBannerUploadFolder($otherBanners->id) . $inputs['previous_image']);
            }
            $otherBanners->save();
        } else {
            $otherBanners->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $otherBanners)
    {
        try {
            //echo '<pre>'; print_r($inputs); die;
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            //Redis::flushDB();
            $redisKeys = Redis::keys('other_banners_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            
            $inputs['circle'] = implode(',', $filteredArray);

            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            /*if(isset($inputs['socid']) && $inputs['socid'] != '' ){
                $filteredSocidArray = array_unique(array_filter($inputs['socid']));
                $inputs['socid'] = implode(',', $filteredSocidArray);
            }else{
                $inputs['socid'] = '';
            }*/
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
                if (isset($otherBanners->$key)) {
                    $otherBanners->$key = $value;
                }
            }
            
            if(isset($inputs['internal_link']) && $inputs['internal_link'] != '' ){ $otherBanners->external_link = ''; }
            if(isset($inputs['external_link']) && $inputs['external_link'] != '' ){ $otherBanners->internal_link = ''; }
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $otherBanners->postpaid_persona = '';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $otherBanners->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $otherBanners->prepaid_persona = 'All';$otherBanners->postpaid_persona = 'All'; }
            
            $save = $otherBanners->save();

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $otherBanners);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Other Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Other Banner']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Other Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Other Banner could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $otherBannersDetails = OtherBanners::find($id);
            if (!empty($otherBannersDetails)) {
                //Redis::flushDB(); // delete all keys
                $redisKeys = Redis::keys('other_banners_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
                $delete = ImageHelper::deleteS3File($otherBannersDetails['banner_name']);
                $otherBannersDetails->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Other Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Other Banner could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
