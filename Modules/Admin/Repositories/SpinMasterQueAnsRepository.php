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

use Modules\Admin\Models\SpinMasterQueAns;
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

class SpinMasterQueAnsRepository extends BaseRepository
{

    /**
     * Create a new SpinMasterRepository instance.
     *
     * @param  Modules\Admin\Models\SpinMaster $model
     * @return void
     */
    public function __construct(SpinMasterQueAns $model)
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
        $response = Cache::tags(SpinMasterQueAns::table())->remember($cacheKey, $this->ttlCache, function() {
            return SpinMasterQueAns::orderBy('updated_at', 'desc')->get();
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
            if(Redis::keys('SpinMasterQueAns_*') != null){  Redis::del(Redis::keys('SpinMasterQueAns_*')); } // delete only pattern match to SpinMasters_
                       
            $SpinMasterQueAns = new $this->model;
            $allColumns = $SpinMasterQueAns->getTableColumns($SpinMasterQueAns->getTable());
            foreach ($inputs as $key => $value) {
                    if (in_array($key, $allColumns)) {
                        $SpinMasterQueAns->$key = $value;
                    }
            }

          // echo "<pre>";print_r($SpinMasterQueAns);die;
            $save = $SpinMasterQueAns->save();
            

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'SpinMasterQueAns']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'SpinMasterQueAns']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'SpinMasterQueAns']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'SpinMasterQueAns']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Update an SpinMaster.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\SpinMaster $SpinMaster
     * @return $result array with status and message elements
     */
    public function update($inputs, $SpinMasterQueAns)
    {
        try {
           // echo '<pre>'; print_r($inputs); die;
            
            //Redis::flushDB();
            if(Redis::keys('SpinMasterQueAns_*') != null){  Redis::del(Redis::keys('SpinMasterQueAns_*')); } // delete only pattern match to SpinMasters_
          
            foreach ($inputs as $key => $value) {
                if (isset($SpinMasterQueAns->$key)) {
                    $SpinMasterQueAns->$key = $value;
                }
            }
            $save = $SpinMasterQueAns->save();


            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'SpinMasterQueAns']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'SpinMasterQueAns']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'SpinMasterQueAns']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("SpinMasterQueAns could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            if(Redis::keys('SpinMasterQueAns_*') != null){  Redis::del(Redis::keys('SpinMasterQueAns_*')); } // delete only pattern match to SpinMasters_
 
            $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
           
            switch ($action) {
                case "update":
                    $SpinMasterIds = explode(',', $inputs['ids']);
                    foreach ($SpinMasterIds as $key => $SpinMasterId) {
                        $SpinMasterDetails = SpinMasterQueAns::find($SpinMasterId);
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
                        $SpinMasterDetails = SpinMasterQueAns::find($SpinMasterId);
                        if (!empty($SpinMasterDetails)) {
                            $SpinMasterDetails->delete();
                        }
                        $resultStatus = true;
                    }
                    break;
                case "copy":
                    $SpinMasterIds = explode(',', $inputs['ids']);
                    foreach ($SpinMasterIds as $key => $SpinMasterId) {
                        $SpinMasterDetails = SpinMasterQueAns::find($SpinMasterId);
                        if (!empty($SpinMasterDetails)) {
                            $SpinMasterDetails['status'] = 0;
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
                        $message = trans('admin::messages.deleted', ['name' => 'Live Music']);
                        break;

                    case 'copy' :
                        $message = trans('admin::messages.copied', ['name' => 'Live Music']);
                        break;
                    case 'update' :
                            $message = trans('admin::messages.updated', ['name' => 'Live Music']);
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
