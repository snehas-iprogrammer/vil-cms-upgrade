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

use Modules\Admin\Models\Videos;
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

class VideosRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(Videos $model)
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
        $response = Cache::tags(Videos::table())->remember($cacheKey, $this->ttlCache, function() {
            return Videos::orderBy('updated_at', 'desc')->get();
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
            // echo '<pre>'; print_r($inputs); die;
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            if(Redis::keys('videos_*') != null){  Redis::del(Redis::keys('videos_*')); } // delete only pattern match to banners_
            $inputs['circle'] = implode(',', $filteredArray);
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);

            if($inputs['lob'] !='Both'){
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
            }else{
                if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){
                    $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                    $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
                }

                if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){
                    $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                    $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
                }

            }
            
            $videos = new $this->model;
            $allColumns = $videos->getTableColumns($videos->getTable());
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $videos->$key = $value;
                }    
            }
            
            if(isset($inputs['internal_link']) && $inputs['internal_link'] != '' ){ $videos->external_link = ''; }
            if(isset($inputs['external_link']) && $inputs['external_link'] != '' ){ $videos->internal_link = ''; }
            if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $videos->postpaid_persona = '';}
            if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $videos->prepaid_persona = ''; }
            if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $videos->prepaid_persona = 'All';$videos->postpaid_persona = 'All'; }

            $save = $videos->save();
            
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Video']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Video']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Video']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Video']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
    
    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $videos)
    {
        try {
            //echo '<pre>'; print_r($inputs); die;
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            //Redis::flushDB();
            if(Redis::keys('videos_*') != null){  Redis::del(Redis::keys('videos_*')); } // delete only pattern match to banners_
            $inputs['circle'] = implode(',', $filteredArray);
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            if($inputs['lob'] !='Both'){
                if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){
                    $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                    $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
                    
                }else{
                    $inputs['postpaid_persona'] = '';
                    $inputs['socid'] = '';

                }

                if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){
                    $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                    $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);
                }else{
                    $inputs['prepaid_persona'] = '';
                    $inputs['plan'] = '';
                }
            }else{
                if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){
                    $filteredPostpaidPersonaArray = array_unique(array_filter($inputs['postpaid_persona']));
                    $inputs['postpaid_persona'] = implode(',', $filteredPostpaidPersonaArray);
                }

                if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){
                    $filteredPrepaidPersonaArray = array_unique(array_filter($inputs['prepaid_persona']));
                    $inputs['prepaid_persona'] = implode(',', $filteredPrepaidPersonaArray);

                }

            }
            
            foreach ($inputs as $key => $value) {
                if (isset($videos->$key)) {
                    $videos->$key = $value;
                }else{
                    if($key == 'cta_name' && $inputs['cta_name'] != null){
                        $videos->cta_name = $value;
                    }
                }
            }
            
            if(isset($inputs['internal_link']) && $inputs['internal_link'] != '' ){ $videos->external_link = ''; }
            if(isset($inputs['external_link']) && $inputs['external_link'] != '' ){ $videos->internal_link = ''; }
            // if(isset($inputs['prepaid_persona']) && $inputs['prepaid_persona'] != '' ){ $videos->postpaid_persona = '';}
            // if(isset($inputs['postpaid_persona']) && $inputs['postpaid_persona'] != '' ){ $videos->prepaid_persona = ''; }
            // if(isset($inputs['lob']) && $inputs['lob'] == 'Both' ){ $videos->prepaid_persona = 'All';$videos->postpaid_persona = 'All'; }
            // if(isset($inputs['plan']) && $inputs['plan'] != '' ){ $videos->plan = ''; }
            // if(isset($inputs['socid']) && $inputs['socid'] != '' ){ $videos->socid = ''; }

            //echo "<pre>";print_r($videos);die;
            $save = $videos->save();
            
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Video']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Video']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Video']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Video could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $videos = Banner::find($id);
            if (!empty($videos)) {
                //Redis::flushDB(); // delete all keys
                if(Redis::keys('videos_*') != null){  Redis::del(Redis::keys('videos_*')); } // delete only pattern match to banners_
                $videos->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Video']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Video could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
