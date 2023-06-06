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

use Modules\Admin\Models\DashboardBanners;
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

class DashboardBannersRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(DashboardBanners $dashboardBanners)
    {
        $this->model = $dashboardBanners;
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
        $response = Cache::tags(DashboardBanners::table())->remember($cacheKey, $this->ttlCache, function() {
            return DashboardBanners::orderBy('id')->get();
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
            //echo '<pre>'; print_r($inputs); die;
            $redisKeys = Redis::keys('DashboardBanners_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $dashboardBanners = new $this->model;
            $allColumns = $dashboardBanners->getTableColumns($dashboardBanners->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $dashboardBanners->image = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $dashboardBanners->$key = $value;
                    }
                }    
            }
            $save = $dashboardBanners->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $dashboardBanners);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Dashboard Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Dashboard Banner']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Dashboard Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Dashboard Banner']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $dashboardBanners) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getDashboardBannersImageUploadFolder($dashboardBanners->id) . $inputs['previous_image']);
            }
            //$dashboardBanners->image = ImageHelper::uploadDashboardBannersImage($inputs['image'], $dashboardBanners);
            $dashboardBanners->image = ImageHelper::uploadDashboardBannersImageS3($inputs['image'], $dashboardBanners);

            $dashboardBanners->save();
        } else if ($inputs['remove'] == 'remove') {
            $dashboardBanners->image = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getDashboardBannersImageUploadFolder($dashboardBanners->id) . $inputs['previous_image']);
            }
            $dashboardBanners->save();
        } else {
            $dashboardBanners->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $dashboardBanners)
    {
        try {
            $redisKeys = Redis::keys('DashboardBanners_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            
            foreach ($inputs as $key => $value) {
                if (isset($dashboardBanners->$key)) {
                    $dashboardBanners->$key = $value;
                }
            }
            $save = $dashboardBanners->save();

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $dashboardBanners);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Dashboard Banner']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Dashboard Banner']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Dashboard Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Dashboard Banner could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $dashboardBanners = DashboardBanners::find($id);
            if (!empty($dashboardBanners)) {
                $redisKeys = Redis::keys('DashboardBanners_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
                $dashboardBanners->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Dashboard Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Dashboard Banner could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
