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

use Modules\Admin\Models\RewardStoreConfig;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class RewardStoreConfigRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(RewardStoreConfig $rewardStoreConfig)
    {
        $this->model = $rewardStoreConfig;
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
        $response = Cache::tags(RewardStoreConfig::table())->remember($cacheKey, $this->ttlCache, function() {
            return RewardStoreConfig::select(['*'])->orderBy('id')->get();
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
            
            $rewardStoreConfig = new $this->model;
            $allColumns = $rewardStoreConfig->getTableColumns($rewardStoreConfig->getTable());
            
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $rewardStoreConfig->$key = $value;
                }
            }
            $rewardStoreConfig->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('Reward_Store_Config_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }
            $save = $rewardStoreConfig->save();
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Reward Store Config']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Reward Store Config']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Reward Store Config']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Reward Store Config']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $rewardStoreConfig)
    {
        try {
            foreach ($inputs as $key => $value) {
                if (isset($rewardStoreConfig->$key)) {
                    $rewardStoreConfig->$key = $value;
                }else{
                    if($key == 'claim_internal_link' && $inputs['claim_internal_link'] != null){
                        $rewardStoreConfig->claim_internal_link = $value;
                    }
                    
                    if($key == 'claim_external_link' && $inputs['claim_external_link'] != null){
                        $rewardStoreConfig->claim_external_link = $value;
                    }
                }
            }
            $rewardStoreConfig->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('Reward_Store_Config_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }
            
            $save = $rewardStoreConfig->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Reward Store Config']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Reward Store Config']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Reward Store Config']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Reward Store Config']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $rewardStoreConfig = RewardStoreConfig::find($id);
            if (!empty($rewardStoreConfig)) {
                $redisKeys = Redis::keys('Reward_Store_Config_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
            
                $rewardStoreConfig->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Reward Store Config']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Reward Store Config']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
