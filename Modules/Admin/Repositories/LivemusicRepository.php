<?php
/**
 * The repository class for managing Livemusic specific actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Livemusic;
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

class LivemusicRepository extends BaseRepository
{

    /**
     * Create a new LivemusicRepository instance.
     *
     * @param  Modules\Admin\Models\Livemusic $model
     * @return void
     */
    public function __construct(Livemusic $model)
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
        $response = Cache::tags(Livemusic::table())->remember($cacheKey, $this->ttlCache, function() {
            return Livemusic::orderBy('updated_at', 'desc')->get();
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
            if($inputs['banner_screen'] == 'MusicDasboard'){
                if(Redis::keys('musicDashboardBanner_*') != null){  Redis::del(Redis::keys('musicDashboardBanner_*')); }
            }else{
                if(Redis::keys('Livemusics_*') != null){  Redis::del(Redis::keys('Livemusics_*')); } // delete only pattern match to Livemusics_
                if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); }
            }
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

            if(isset($inputs['red_hierarchy']) && $inputs['red_hierarchy'] != '' ){
                $filteredred_hierarchyArray = array_unique(array_filter($inputs['red_hierarchy']));
                $inputs['red_hierarchy'] = implode(',', $filteredred_hierarchyArray);
            }else{
                $inputs['red_hierarchy'] = '';
            }
            
            $Livemusic = new $this->model;
            $allColumns = $Livemusic->getTableColumns($Livemusic->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $Livemusic->banner_name = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $Livemusic->$key = $value;
                    }
                }    
            }
            
            if($inputs['start_date_time'] != ''){ $Livemusic->start_date_time = $inputs['start_date_time']; } 
            if($inputs['end_date_time'] != ''){ $Livemusic->end_date_time = $inputs['end_date_time']; }
           // if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $Livemusic->socid ='';}
           // if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $Livemusic->prepaid_persona = 'All';$Livemusic->postpaid_persona = 'All';$Livemusic->socid ='All SOCID'; }
           // echo "<pre>";print_r($Livemusic);die;
            $save = $Livemusic->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $Livemusic);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Livemusic']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Livemusic']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Livemusic']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Livemusic']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $Livemusic) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            // if (!empty($inputs['previous_image'])) {
            //     File::Delete(public_path() . ImageHelper::getLivemusicUploadFolder($Livemusic->id) . $inputs['previous_image']);
            // }
            if (isset($inputs['previous_image'])) {
                $delete = ImageHelper::deleteS3File($inputs['previous_image']);
            }

            //$Livemusic->Livemusic_name = ImageHelper::uploadLivemusic($inputs['image'], $Livemusic);
            $Livemusic->banner_name = ImageHelper::uploadLivemusicS3($inputs['image'], $Livemusic);

            $Livemusic->save();
        } else if ($inputs['remove'] == 'remove') {
            $Livemusic->banner_name = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getLivemusicUploadFolder($Livemusic->id) . $inputs['previous_image']);
            }
            $Livemusic->save();
        } else {
            $Livemusic->save();
        }
        return true;
    }

    /**
     * Update an Livemusic.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Livemusic $Livemusic
     * @return $result array with status and message elements
     */
    public function update($inputs, $Livemusic)
    {
        try {
            if($inputs['banner_screen'] == 'MusicDasboard'){
                if(Redis::keys('musicDashboardBanner_*') != null){  Redis::del(Redis::keys('musicDashboardBanner_*')); }
            }else{
                if(Redis::keys('Livemusics_*') != null){  Redis::del(Redis::keys('Livemusics_*')); } // delete only pattern match to Livemusics_
                if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); }
            }
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            //Redis::flushDB();
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
            
            if(isset($inputs['red_hierarchy']) && $inputs['red_hierarchy'] != '' ){
                $filteredred_hierarchyArray = array_unique(array_filter($inputs['red_hierarchy']));
                $inputs['red_hierarchy'] = implode(',', $filteredred_hierarchyArray);
            }else{
                $inputs['red_hierarchy'] = '';
            }
            
            foreach ($inputs as $key => $value) {
                if (isset($Livemusic->$key)) {
                    $Livemusic->$key = $value;
                }else{
                   
                    
                    if($key == 'login_type' && $inputs['login_type'] != null){
                        $Livemusic->login_type = $value;
                    }
                }
            }
            
            if($inputs['start_date_time'] != ''){ $Livemusic->start_date_time = $inputs['start_date_time']; } 
            if($inputs['end_date_time'] != ''){ $Livemusic->end_date_time = $inputs['end_date_time']; }
           // if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $Livemusic->socid ='';}
          //  if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $Livemusic->prepaid_persona = 'All';$Livemusic->postpaid_persona = 'All';$Livemusic->socid ='All SOCID'; }
            
            $save = $Livemusic->save();
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $Livemusic);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Livemusic']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Livemusic']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Livemusic']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Livemusic could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Delete actions on Livemusics
     *
     * @param  int  $inputs
     * @return int
     */
    public function deleteAction($inputs)
    {
        try {
            if(Redis::keys('Livemusics_*') != null){  Redis::del(Redis::keys('Livemusics_*')); } // delete only pattern match to Livemusics_
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); }
            
            $resultStatus = false;
            $id = $inputs['ids'];   
            $action = $inputs['action'];
           
            switch ($action) {
                case "update":
                    $LivemusicIds = explode(',', $inputs['ids']);
                    foreach ($LivemusicIds as $key => $LivemusicId) {
                        $LivemusicDetails = Livemusic::find($LivemusicId);
                        if (!empty($LivemusicDetails)) {
                            switch ($inputs['value']) {
                                case '1': $LivemusicDetails->status = 1;
                                    break;
                                case '0': $LivemusicDetails->status = 0;
                                    break;
                            }
                            $LivemusicDetails->save();
                            $resultStatus = true;
                        }
                    }
                    break;
                case "delete":
                    $LivemusicIds = explode(',', $inputs['ids']);
                    foreach ($LivemusicIds as $key => $LivemusicId) {
                        $LivemusicDetails = Livemusic::find($LivemusicId);
                        if (!empty($LivemusicDetails)) {
                            $delete = ImageHelper::deleteS3File($LivemusicDetails['banner_name']);
                            $LivemusicDetails->delete();
                        }
                        $resultStatus = true;
                    }
                    break;
                case "copy":
                    $LivemusicIds = explode(',', $inputs['ids']);
                    foreach ($LivemusicIds as $key => $LivemusicId) {
                        $LivemusicDetails = Livemusic::find($LivemusicId);
                        if (!empty($LivemusicDetails)) {
                            $LivemusicDetails['status'] = 0;
                            $LivemusicDetails['banner_name'] = '';
                            $newLivemusic = $LivemusicDetails->replicate()->save();                            
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
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Livemusic']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Livemusic could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
