<?php
/**
 * The repository class for managing SpinMaster specific actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\SpinMaster;
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

class SpinMasterRepository extends BaseRepository
{

    /**
     * Create a new SpinMasterRepository instance.
     *
     * @param  Modules\Admin\Models\SpinMaster $model
     * @return void
     */
    public function __construct(SpinMaster $model)
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
        $response = Cache::tags(SpinMaster::table())->remember($cacheKey, $this->ttlCache, function() {
            return SpinMaster::orderBy('updated_at', 'desc')->get();
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
           
            //Redis::flushDB(); // delete all keys
            if(Redis::keys('SpinMasters_*') != null){  Redis::del(Redis::keys('SpinMasters_*')); } // delete only pattern match to SpinMasters_
                       
            $SpinMaster = new $this->model;
            $allColumns = $SpinMaster->getTableColumns($SpinMaster->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $SpinMaster->logo_image = '';
                }else if($key == 'overlay_image' ){
                    $SpinMaster->overlay_image = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $SpinMaster->$key = $value;
                    }
                }    
            }

         //   echo "<pre>";print_r($SpinMaster);die;
            $save = $SpinMaster->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $SpinMaster);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'SpinMaster']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'SpinMaster']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'SpinMaster']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'SpinMaster']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $SpinMaster) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image']) || !empty($inputs['overlay_image'])) {
            
            if(isset($inputs['image'])){
                $SpinMaster->logo_image = ImageHelper::uploadSpinMasterS3($inputs['image'], $SpinMaster);
            }
            if(isset($inputs['overlay_image'])){
                $SpinMaster->overlay_image = ImageHelper::uploadSpinMasterS3($inputs['overlay_image'], $SpinMaster);
            }
            $SpinMaster->save();
        } else if ($inputs['remove'] == 'remove') {
            $SpinMaster->logo_image = '';
            $SpinMaster->overlay_image = '';
            if (!empty($inputs['previous_image_overlay_image'])) {
                File::Delete(public_path() . ImageHelper::getSpinMasterUploadFolder($SpinMaster->id) . $inputs['previous_image_overlay_image']);
            }
            if (!empty($inputs['previous_image_logo_image'])) {
                File::Delete(public_path() . ImageHelper::getSpinMasterUploadFolder($SpinMaster->id) . $inputs['previous_image_logo_image']);
            }
            $SpinMaster->save();
        } else {
            $SpinMaster->save();
        }
        return true;
    }

    /**
     * Update an SpinMaster.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\SpinMaster $SpinMaster
     * @return $result array with status and message elements
     */
    public function update($inputs, $SpinMaster)
    {
        try {
           //echo '<pre>'; print_r($inputs); die;
            
            //Redis::flushDB();
            if(Redis::keys('SpinMasters_*') != null){  Redis::del(Redis::keys('SpinMasters_*')); } // delete only pattern match to SpinMasters_
          
            foreach ($inputs as $key => $value) {
                if (isset($SpinMaster->$key)) {
                    $SpinMaster->$key = $value;
                }
            }
            $save = $SpinMaster->save();

            // foreach ($inputs as $key => $value) {
            //     if($key == 'image' ){
            //         $SpinMaster->logo_image = '';
            //     }else if($key == 'overlay_image' ){
            //         $SpinMaster->overlay_image = '';
            //     }  
            // }

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $SpinMaster);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'SpinMaster']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'SpinMaster']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'SpinMaster']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("SpinMaster could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Delete actions on SpinMasters
     *
     * @param  int  $inputs
     * @return int
     */
    public function deleteAction($inputs)
    {
        try {
            if(Redis::keys('SpinMasters_*') != null){  Redis::del(Redis::keys('SpinMasters_*')); } // delete only pattern match to SpinMasters_
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); }
            
            $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
           
            switch ($action) {
                case "update":
                    $SpinMasterIds = explode(',', $inputs['ids']);
                    foreach ($SpinMasterIds as $key => $SpinMasterId) {
                        $SpinMasterDetails = SpinMaster::find($SpinMasterId);
                        if (!empty($SpinMasterDetails)) {
                            switch ($inputs['value']) {
                                case '1': $SpinMasterDetails->status = 1;
                                    break;
                                case '0': $SpinMasterDetails->status = 0;
                                    break;
                            }
                            $SpinMasterDetails->save();
                            $resultStatus = true;
                        }
                    }
                    break;
                case "delete":
                    $SpinMasterIds = explode(',', $inputs['ids']);
                    foreach ($SpinMasterIds as $key => $SpinMasterId) {
                        $response = DB::select('select *  from reward_history where spin_id='.$SpinMasterId);
                        if(empty($response)){
                            $SpinMasterDetails = SpinMaster::find($SpinMasterId);
                            if (!empty($SpinMasterDetails)) {
                                $delete = ImageHelper::deleteS3File($SpinMasterDetails['logo_image']);
                                $delete = ImageHelper::deleteS3File($SpinMasterDetails['overlay_image']);
                                $SpinMasterDetails->delete();
                            }
                            $resultStatus = true;
                        }
                    }
                    break;
                case "copy":
                    $SpinMasterIds = explode(',', $inputs['ids']);
                    foreach ($SpinMasterIds as $key => $SpinMasterId) {
                        $SpinMasterDetails = SpinMaster::find($SpinMasterId);
                        if (!empty($SpinMasterDetails)) {
                            $SpinMasterDetails['status'] = 0;
                            $SpinMasterDetails['logo_image'] = '';
                            $SpinMasterDetails['overlay_image'] = '';
                            $newSpinMaster = $SpinMasterDetails->replicate()->save();                            
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
                        $message = trans('admin::messages.deleted', ['name' => 'Data']);
                        break;

                    case 'copy' :
                        $message = trans('admin::messages.copied', ['name' => 'Data']);
                        break;
                    case 'update' :
                            $message = trans('admin::messages.updated', ['name' => 'Data']);
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
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'SpinMaster']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("SpinMaster could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
