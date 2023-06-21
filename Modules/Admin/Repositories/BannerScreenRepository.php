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

use Modules\Admin\Models\BannerScreen;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class BannerScreenRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(BannerScreen $Bannerscreen)
    {
        $this->model = $Bannerscreen;
    }

    /**
     * Get a listing of the resource.
     *
     * @return Response
     */
    public function listBannerScreenData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and DaBannerScreenase
        $response = Cache::tags(BannerScreen::table())->remember($cacheKey, $this->ttlCache, function() {
            return BannerScreen::orderBY('id')->where('status',1)->pluck('name','id');
        });
      
        return $response;
    }

    public function data($params = [])
    {

        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        //Cache::tags not suppport with files and DaBannerScreenase
        $response = Cache::tags(BannerScreen::table())->remember($cacheKey, $this->ttlCache, function() {
            return BannerScreen::orderBy('id')->get();
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
            if(Redis::keys("all_remote_banner_screens") != null){  Redis::del(Redis::keys("all_remote_banner_screens")); }
            if(Redis::keys('checkBanner_*') != null){  Redis::del(Redis::keys('checkBanner_*')); }
            $Bannerscreen = new $this->model;
            $allColumns = $Bannerscreen->gettableColumns($Bannerscreen->gettable());
            foreach ($inputs as $key => $value) {
               
                if (in_array($key, $allColumns)) {
                    $Bannerscreen->$key = $value;
                }
            }
           
            $save = $Bannerscreen->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'BannerScreen']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'BannerScreen']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'BannerScreen Name']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'BannerScreen Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $Bannerscreen)
    {
        try {
            if(Redis::keys("all_remote_banner_screens") != null){  Redis::del(Redis::keys("all_remote_banner_screens")); }
            if(Redis::keys('checkBanner_*') != null){  Redis::del(Redis::keys('checkBanner_*')); }
            if(!empty($inputs)){
                if(Redis::keys($inputs['screen_name'].'_*') != null){  Redis::del(Redis::keys($inputs['screen_name'].'_*')); }
            }
            //print_r($inputs);die;
            foreach ($inputs as $key => $value) {
                if (isset($Bannerscreen->$key)) {
                    $Bannerscreen->$key = $value;
                }
            }
            unset($Bannerscreen->screen_name);
            $save = $Bannerscreen->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'BannerScreen Name']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'BannerScreen Name']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'ilent Ota']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'BannerScreen Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $Bannerscreen = BannerScreen::find($id);
            
            if (!empty($Bannerscreen)) {
                {
                    if(Redis::keys("all_remote_banner_screens") != null){  Redis::del(Redis::keys("all_remote_banner_screens")); }
                    if(Redis::keys('checkBanner_*') != null){  Redis::del(Redis::keys('checkBanner_*')); }
                }        
                if(!empty($Bannerscreen)){
                    if(Redis::keys($Bannerscreen['screen_name'].'_*') != null){  Redis::del(Redis::keys($Bannerscreen['screen_name'].'_*')); }
                }
                $Bannerscreen->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'BannerScreen Name']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'BannerScreen Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
