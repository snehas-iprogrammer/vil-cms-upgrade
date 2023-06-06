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

use Modules\Admin\Models\QuickLinks;
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

class QuickLinksRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(QuickLinks $model)
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
        $response = Cache::tags(QuickLinks::table())->remember($cacheKey, $this->ttlCache, function() {
            return QuickLinks::orderBy('id')->get();
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
            //echo 'DD SS<pre>'; print_r($quickLinks); die;            
            $redisKeys = Redis::keys('VersionWiseQuickLinks_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            $filteredRedHierarchyArray = array_unique(array_filter($inputs['red_hierarchy']));
            $inputs['red_hierarchy'] = implode(',', $filteredRedHierarchyArray);
            
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
            
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $inputs['persona'] = $inputs['prepaid_persona']; }
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $inputs['persona'] = $inputs['postpaid_persona']; }
            $inputs['referred_json'] = trim($inputs['referred_json']);
            $quickLinks = new $this->model;
            $allColumns = $quickLinks->getTableColumns($quickLinks->getTable());
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $quickLinks->$key = $value;
                }    
            }
            
            $save = $quickLinks->save();
            
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Quick Link']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Quick Link']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Quick Link']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Quick Link']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
    
    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $quickLinks)
    {
        try {
            
            $redisKeys = Redis::keys('VersionWiseQuickLinks_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            
            $filteredRedHierarchyArray = array_unique(array_filter($inputs['red_hierarchy']));
            $inputs['red_hierarchy'] = implode(',', $filteredRedHierarchyArray);
            
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
            
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $inputs['persona'] = $inputs['prepaid_persona']; }
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $inputs['persona'] = $inputs['postpaid_persona']; }
            $inputs['referred_json'] = trim($inputs['referred_json']);
            
            foreach ($inputs as $key => $value) {
                if (isset($quickLinks->$key)) {
                    $quickLinks->$key = $value;
                }
            }
            
            // echo 'ss : <pre>'; print_r($inputs['postpaid_persona']); die;
            $save = $quickLinks->save();
            
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Quick Link']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Quick Link']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Quick Link']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Quick Link could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $quickLinksDetails = QuickLinks::find($id);
            if (!empty($quickLinksDetails)) {
                //Redis::flushDB(); // delete all keys
                $redisKeys = Redis::keys('VersionWiseQuickLinks_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
                $quickLinksDetails->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Quick Link']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Quick Link could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
