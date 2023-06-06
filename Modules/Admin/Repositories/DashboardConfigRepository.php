<?php
/**
 * The repository class for managing faq categories specific actions.
 *
 *
 * @author Sachin Sonune <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\DashboardConfig;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class DashboardConfigRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(DashboardConfig $dashboardConfig)
    {
        $this->model = $dashboardConfig;
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
        $response = Cache::tags(DashboardConfig::table())->remember($cacheKey, $this->ttlCache, function() {
            return DashboardConfig::select(['*'])->orderBy('id')->get();
        });

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Form data posted from ajax $inputs
     * @return $result array with status and message elements
     */
    public function create($inputs, $user_id = null)
    {
        try {
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            $inputs['circle'] = implode(',', $filteredArray);
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
            $dashboardConfig = new $this->model;
            $allColumns = $dashboardConfig->getTableColumns($dashboardConfig->getTable());
            //$inputs['header_menu'] = trim($inputs['header_menu']);
            $inputs['rail_sequence'] = trim($inputs['rail_sequence']);
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

            if(isset($inputs['red_hierarchy']) && isset($inputs['red_hierarchy']) && $inputs['red_hierarchy'] != '' ){
                $filteredred_hierarchy = array_unique(array_filter($inputs['red_hierarchy']));
                $inputs['red_hierarchy'] = implode(',', $filteredred_hierarchy);
            }else{
                $inputs['red_hierarchy'] = '';
            }
           
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $dashboardConfig->$key = $value;
                }
            }
            $dashboardConfig->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('Dashboard_Config_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }

            $save = $dashboardConfig->save();
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Dashboard Config']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Dashboard Config']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Dashboard Config']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Dashboard Config']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Update an faq category.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\FaqCategory $faqCategory
     * @return $result array with status and message elements
     * @return void
     */
    public function update($inputs, $dashboardConfig)
    {
        try {
            $filteredArray = array_unique(array_filter($inputs['circle']));
            $inputs['circle'] = implode(',', $filteredArray);
            
            $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
            $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            
          //  $inputs['header_menu'] = trim($inputs['header_menu']);
            $inputs['rail_sequence'] = trim($inputs['rail_sequence']);
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

            if(isset($inputs['red_hierarchy']) && isset($inputs['red_hierarchy']) && $inputs['red_hierarchy'] != '' ){
                $filteredred_hierarchy = array_unique(array_filter($inputs['red_hierarchy']));
                $inputs['red_hierarchy'] = implode(',', $filteredred_hierarchy);
            }else{
                $inputs['red_hierarchy'] = '';
            }

            foreach ($inputs as $key => $value) {
                if (isset($dashboardConfig->$key)) {
                    $dashboardConfig->$key = $value;
                }else{
                    if($key == 'prepaid_persona' && $inputs['prepaid_persona'] != ''){
                        $dashboardConfig->prepaid_persona = $value;
                    }
                    if($key == 'postpaid_persona' && $inputs['postpaid_persona'] != ''){
                        $dashboardConfig->postpaid_persona = $value;
                    }
                    if($key == 'red_hierarchy' && $inputs['red_hierarchy'] != ''){
                        $dashboardConfig->red_hierarchy = $value;
                    }
                }
            }
            $dashboardConfig->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('Dashboard_Config_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }
            
            $save = $dashboardConfig->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Dashboard Config']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Dashboard Config']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Dashboard Config']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Dashboard Config']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $resultStatus = false;
            $id = $inputs['ids'];
            $dashboardConfig = DashboardConfig::find($id);
            if (!empty($dashboardConfig)) {
                $redisKeys = Redis::keys('Dashboard_Config_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
            
                $dashboardConfig->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Dashboard Config']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Dashboard Config']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
