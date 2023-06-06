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

use Modules\Admin\Models\AnonScreenDetails;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class AnonScreenDetailsRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(AnonScreenDetails $anonScreenDetails)
    {
        $this->model = $anonScreenDetails;
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
        $response = Cache::tags(AnonScreenDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return AnonScreenDetails::select(['id', 'screen_id', 'screen_header', 'screen_title', 'screen_description', 'faqs_json', 'mrps_json', 'screen_packs_title', 'screen_packs_button_txt', 'screen_packs_button_link', 'status'])->orderBy('id')->get();
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
            $anonScreenDetails = new $this->model;
            $allColumns = $anonScreenDetails->getTableColumns($anonScreenDetails->getTable());
            $inputs['screen_description'] = trim($inputs['screen_description']);
            $inputs['faqs_json'] = trim($inputs['faqs_json']);
            $inputs['mrps_json'] = trim($inputs['mrps_json']);
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $anonScreenDetails->$key = $value;
                }
            }
            $anonScreenDetails->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('Anon_Screen_Details_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }
            $save = $anonScreenDetails->save();
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Anon Screen Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Anon Screen Details']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Anon Screen Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Anon Screen Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $anonScreenDetails)
    {
        try {
            $inputs['screen_description'] = trim($inputs['screen_description']);
            $inputs['faqs_json'] = trim($inputs['faqs_json']);
            $inputs['mrps_json'] = trim($inputs['mrps_json']);
            foreach ($inputs as $key => $value) {
                if (isset($anonScreenDetails->$key)) {
                    $anonScreenDetails->$key = $value;
                }
            }
            $anonScreenDetails->mrps_json = $inputs['mrps_json'];
            $anonScreenDetails->status = isset($inputs['status']) ? $inputs['status'] : 0;
            $redisKeys = Redis::keys('Anon_Screen_Details_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }
            
            $save = $anonScreenDetails->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Anon Screen Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Anon Screen Details']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Anon Screen Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Anon Screen Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $anonScreenDetails = AnonScreenDetails::find($id);
            if (!empty($anonScreenDetails)) {
                $redisKeys = Redis::keys('Anon_Screen_Details_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
            
                $anonScreenDetails->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Anon Screen Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Anon Screen Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
