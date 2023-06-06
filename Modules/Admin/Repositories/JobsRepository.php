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

use Modules\Admin\Models\Jobs;
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

class JobsRepository extends BaseRepository
{

    /**
     * Create a new JobsRepository instance.
     *
     * @param  Modules\Admin\Models\Jobs $model
     * @return void
     */
    public function __construct(Jobs $model)
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
        $response = Cache::tags(Jobs::table())->remember($cacheKey, $this->ttlCache, function() {
            return Jobs::orderBy('updated_at', 'desc')->get();
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
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            //Redis::flushDB(); // delete all keys
            if(Redis::keys('getJobsandEducationDetails_*') != null){  Redis::del(Redis::keys('getJobsandEducationDetails_*')); }
            
            
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
            
            $jobs = new $this->model;
            $allColumns = $jobs->getTableColumns($jobs->getTable());
            foreach ($inputs as $key => $value) {
                
                    if (in_array($key, $allColumns)) {
                        $jobs->$key = $value;
                    }
                  
            }

            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $jobs->postpaid_persona = '';$jobs->socid ='';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $jobs->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $jobs->prepaid_persona = 'All';$jobs->postpaid_persona = 'All';$jobs->socid ='All SOCID'; }
           
            $save = $jobs->save();
            
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Jobs Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Jobs Details']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Jobs Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Jobs Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }


    /**
     * Update an Jobs .
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Jobs $jobs
     * @return $result array with status and message elements
     */
    public function update($inputs, $jobs)
    {
        try {
           
            if(Redis::keys('getJobsandEducationDetails_*') != null){  Redis::del(Redis::keys('getJobsandEducationDetails_*')); }
        
            
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
                if (isset($jobs->$key)) {
                    $jobs->$key = $value;
                }
            }
            
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $jobs->postpaid_persona = '';$jobs->socid ='';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $jobs->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $jobs->prepaid_persona = 'All';$jobs->postpaid_persona = 'All';$jobs->socid ='All SOCID'; }
           
            $save = $jobs->save();
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Jobs Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Jobs Details']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Jobs Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Jobs Details could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            
            if(Redis::keys('getJobsandEducationDetails_*') != null){  Redis::del(Redis::keys('getJobsandEducationDetails_*')); }

            $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
           
            switch ($action) {
                case "update":
                    $jobsIds = explode(',', $inputs['ids']);
                    foreach ($jobsIds as $key => $jobsId) {
                        $jobsDetails = Jobs::find($jobsId);
                        if (!empty($jobsDetails)) {
                            switch ($inputs['value']) {
                                case '1': $jobsDetails->status = 1;
                                    break;
                                case '0': $jobsDetails->status = 0;
                                    break;
                            }
                            $jobsDetails->save();
                            $resultStatus = true;
                        }
                    }
                    break;
                case "delete":
                    $jobsIds = explode(',', $inputs['ids']);
                    foreach ($jobsIds as $key => $jobsId) {
                        $jobsDetails = Jobs::find($jobsId);
                        if (!empty($jobsDetails)) {
                            $jobsDetails->delete();
                        }
                        $resultStatus = true;
                    }
                    break;
                case "copy":
                    $jobsIds = explode(',', $inputs['ids']);
                    foreach ($jobsIds as $key => $jobsId) {
                        $jobsDetails = Jobs::find($jobsId);
                        if (!empty($jobsDetails)) {
                            $jobsDetails['status'] = 0;
                            $newBanner = $jobsDetails->replicate()->save();                            
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
