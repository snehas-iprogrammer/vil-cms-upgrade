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

use Modules\Admin\Models\QuickRechargeDetails;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class QuickRechargeDetailsRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(QuickRechargeDetails $quickRechargeDetails)
    {
        $this->model = $quickRechargeDetails;
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
        $response = Cache::tags(QuickRechargeDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return QuickRechargeDetails::select([
                    'id', 'circle', 'mrp', 'route_name', 'referred_json', 'status'
                ])->orderBy('id')->get();
        });

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listAllRechargeOffersData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(QuickRechargeDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return QuickRechargeDetails::orderBY('id')->lists('mrp', 'id');
        });

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listCategoryData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(QuickRechargeDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return QuickRechargeDetails::orderBY('id')->lists('mrp', 'id');
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
            $quickRechargeDetails = new $this->model;
            
            $filteredArray = array_unique(array_filter($inputs['circle']));
            $inputs['circle'] = implode(',', $filteredArray);
            $allColumns = $quickRechargeDetails->getTableColumns($quickRechargeDetails->getTable());
            $inputs['referred_json'] = trim($inputs['referred_json']);
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $quickRechargeDetails->$key = $value;
                }
            }
            $quickRechargeDetails->status = isset($inputs['status']) ? $inputs['status'] : 0;
            // Redis::del(Redis::keys('Quick_Recharge_getQuickRecharge_*'));
            $redisKeys = Redis::keys('Quick_Recharge_getQuickRecharge_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $save = $quickRechargeDetails->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Quick Recharge Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Quick Recharge Details']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Quick Recharge Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Quick Recharge Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $quickRechargeDetails)
    {
        try {
            $filteredArray = array_unique(array_filter($inputs['circle']));
            $inputs['circle'] = implode(',', $filteredArray);
            $inputs['referred_json'] = trim($inputs['referred_json']);
            foreach ($inputs as $key => $value) {
                if (isset($quickRechargeDetails->$key)) {
                    $quickRechargeDetails->$key = $value;
                }
            }
            $quickRechargeDetails->status = isset($inputs['status']) ? $inputs['status'] : 0;
            // Redis::del(Redis::keys('Quick_Recharge_getQuickRecharge_*'));
            $redisKeys = Redis::keys('Quick_Recharge_getQuickRecharge_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $save = $quickRechargeDetails->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Quick Recharge Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Quick Recharge Details']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Quick Recharge Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Quick Recharge Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $quickRechargeDetails = QuickRechargeDetails::find($id);
            if (!empty($quickRechargeDetails)) {
                // Redis::del(Redis::keys('Quick_Recharge_getQuickRecharge_*'));
                $redisKeys = Redis::keys('Quick_Recharge_getQuickRecharge_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
                $quickRechargeDetails->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Quick Recharge Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Quick Recharge Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
