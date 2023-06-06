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

use Modules\Admin\Models\MasterQuickLink;
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

class MasterQuickLinkRepository extends BaseRepository
{

    /**
     * Create a new MasterQuickLinkRepository instance.
     *
     * @param  Modules\Admin\Models\MasterQuickLink $model
     * @return void
     */
    public function __construct(MasterQuickLink $model)
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
        $response = Cache::tags(MasterQuickLink::table())->remember($cacheKey, $this->ttlCache, function() {
            return MasterQuickLink::orderBy('updated_at', 'desc')->get();
        });

        return $response;
    }


       /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listMasterQuickLinkData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(MasterQuickLink::table())->remember($cacheKey, $this->ttlCache, function() {
            return MasterQuickLink::orderBY('id')->where('status',1)->lists('name','id');
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
        
            $redisKeys = Redis::keys('VersionWiseQuickLinks_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }             $masterquicklink = new $this->model;
            
            $allColumns = $masterquicklink->getTableColumns($masterquicklink->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'imageUrl' ){
                    $masterquicklink->imageUrl = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $masterquicklink->$key = $value;
                    }
                }    
            }
 
            $save = $masterquicklink->save();
            $this->updateCategoryIcon($inputs, $masterquicklink);
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Master Quick Link']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Master Quick Link']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Master Quick Link']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Master Quick Link']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    public function updateCategoryIcon($inputs, $masterquicklink) {
        Cache::tags('home')->flush();
        if (!empty($inputs['imageUrl'])) {
            //unlink old file
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getMasterQuicklinkImageUploadFolder($masterquicklink->id) . $inputs['previous_image']);
            }
            $masterquicklink->imageUrl = ImageHelper::uploadMasterQuicklinkImageS3($inputs['imageUrl'], $masterquicklink);
            $masterquicklink->save();
        } else if ($inputs['remove'] == 'remove') {
            $masterquicklink->imageUrl = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getMasterQuicklinkImageUploadFolder($masterquicklink->id) . $inputs['previous_image']);
            }
            $masterquicklink->save();
        } else {
            $masterquicklink->save();
        }
        return true;
    }
 

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $masterquicklink)
    {
        try {
             $redisKeys = Redis::keys('VersionWiseQuickLinks_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            
            foreach ($inputs as $key => $value) {
                if (isset($masterquicklink->$key)) {
                    $masterquicklink->$key = $value;
                }
            }
            $save = $masterquicklink->save();
            $this->updateCategoryIcon($inputs, $masterquicklink);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Master Quick Link']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Master Quick Link']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Master Quick Link']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Master Quick Link could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
           

            $masterquicklink = MasterQuickLink::find($id);
            if (!empty($masterquicklink)) {
        
                if(Redis::keys('masterquicklink_*') != null){  Redis::del(Redis::keys('masterquicklink_*')); } // delete only pattern match to banners_
             
                $masterquicklink->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Master Quick Link']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Master Quick Link could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
