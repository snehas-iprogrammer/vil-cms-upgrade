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

use Modules\Admin\Models\RechargeOffers;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class RechargeOffersRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(RechargeOffers $rechargeOffers)
    {
        $this->model = $rechargeOffers;
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
        $response = Cache::tags(RechargeOffers::table())->remember($cacheKey, $this->ttlCache, function() {
            return RechargeOffers::select([
                    'id', 'segment_name', 'route_name', 'referred_json', 'mrp_sequence_data', 'status'
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
        $response = Cache::tags(RechargeOffers::table())->remember($cacheKey, $this->ttlCache, function() {
            return RechargeOffers::orderBY('id')->lists('segment_name', 'id');
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
        $response = Cache::tags(RechargeOffers::table())->remember($cacheKey, $this->ttlCache, function() {
            return RechargeOffers::orderBY('id')->lists('segment_name', 'id');
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
            if(Redis::keys('New_Recharge_Offers*') != null){  
                Redis::del(Redis::keys('New_Recharge_Offers*'));
            }

            $rechargeOffers = new $this->model;
            $allColumns = $rechargeOffers->getTableColumns($rechargeOffers->getTable());
            $inputs['referred_json'] = trim($inputs['referred_json']);
            $inputs['mrp_sequence_data'] = trim($inputs['mrp_sequence_data']);
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $rechargeOffers->$key = $value;
                }
            }
            $rechargeOffers->status = isset($inputs['status']) ? $inputs['status'] : 0;
            Redis::del('New_Recharge_Offers');
            $save = $rechargeOffers->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Recharge Offer']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Recharge Offer']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Recharge Offer']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Recharge Offer']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $rechargeOffers)
    {
        try {
            if(Redis::keys('New_Recharge_Offers*') != null){  
                Redis::del(Redis::keys('New_Recharge_Offers*'));
            }
            
            $inputs['referred_json'] = trim($inputs['referred_json']);
            $inputs['mrp_sequence_data'] = trim($inputs['mrp_sequence_data']);
            foreach ($inputs as $key => $value) {
                if (isset($rechargeOffers->$key)) {
                    $rechargeOffers->$key = $value;
                }else{
                    if($key == 'mrp_sequence_data' && $inputs['mrp_sequence_data'] != null){
                        $rechargeOffers->mrp_sequence_data = $value;
                    }
                }
            }

            $rechargeOffers->status = isset($inputs['status']) ? $inputs['status'] : 0;
            Redis::del('New_Recharge_Offers');
            $save = $rechargeOffers->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Recharge Offer']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Recharge Offer']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Recharge Offer']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Recharge Offer']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $rechargeOffers = RechargeOffers::find($id);
            if (!empty($rechargeOffers)) {
                Redis::del('New_Recharge_Offers');
                $rechargeOffers->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Recharge Offer']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Recharge Offer']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
