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

use Modules\Admin\Models\SilentOtas;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class SilentOtasRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(SilentOtas $silentOtas)
    {
        $this->model = $silentOtas;
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
        $response = Cache::tags(SilentOtas::table())->remember($cacheKey, $this->ttlCache, function() {
            return SilentOtas::select([
                    '*'
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
        $response = Cache::tags(SilentOtas::table())->remember($cacheKey, $this->ttlCache, function() {
            return SilentOtas::orderBY('id')->lists('mrp', 'id');
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
        $response = Cache::tags(SilentOtas::table())->remember($cacheKey, $this->ttlCache, function() {
            return SilentOtas::orderBY('id')->lists('mrp', 'id');
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
            $silentOtas = new $this->model;
            $allColumns = $silentOtas->getTableColumns($silentOtas->getTable());
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $silentOtas->$key = $value;
                }
            }
            $silentOtas->new_features = isset($inputs['new_features']) ? $inputs['new_features'] : null;
            $silentOtas->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('SilentOTA_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $save = $silentOtas->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Silent Ota']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Silent Ota']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Silent Ota']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Silent Ota']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $silentOtas)
    {
        try {
            foreach ($inputs as $key => $value) {
                if (isset($silentOtas->$key)) {
                    $silentOtas->$key = $value;
                }
            }
            $silentOtas->new_features = isset($inputs['new_features']) ? $inputs['new_features'] : null;
            $silentOtas->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('SilentOTA_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $save = $silentOtas->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Silent Ota']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Silent Ota']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'ilent Ota']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Silent Ota']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $silentOtas = SilentOtas::find($id);
            if (!empty($silentOtas)) {
                $redisKeys = Redis::keys('SilentOTA_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
                $silentOtas->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Silent Ota']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Silent Ota']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
