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

use Modules\Admin\Models\GameScreen;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class GameScreenRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(GameScreen $gamescreen)
    {
        $this->model = $gamescreen;
    }

    /**
     * Get a listing of the resource.
     *
     * @return Response
     */
    public function listGameScreenData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and DaGameScreenase
        $response = Cache::tags(GameScreen::table())->remember($cacheKey, $this->ttlCache, function() {
            return GameScreen::orderBY('id')->where('status',1)->lists('name','id');
        });
      
        return $response;
    }

    public function data($params = [])
    {

        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        //Cache::tags not suppport with files and DaGameScreenase
        $response = Cache::tags(GameScreen::table())->remember($cacheKey, $this->ttlCache, function() {
            return GameScreen::orderBy('id')->get();
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
            
            if(Redis::keys('gameRailSequence_*') != null){  Redis::del(Redis::keys('gameRailSequence_*')); } 
            if(Redis::keys('upperGameBanner_*') != null){  Redis::del(Redis::keys('upperGameBanner_*')); }
            if(Redis::keys('featuredGameBanner_*') != null){  Redis::del(Redis::keys('featuredGameBanner_*')); } 
            if(Redis::keys('trendingNowGameBanner_*') != null){  Redis::del(Redis::keys('trendingNowGameBanner_*')); } 
            if(Redis::keys('socialGamingBanner_*') != null){  Redis::del(Redis::keys('socialGamingBanner_*')); } 
            if(Redis::keys('bottomGameBanner_*') != null){  Redis::del(Redis::keys('bottomGameBanner_*')); } 
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); } 
            if(Redis::keys('yourFavoriteBanner_*') != null){  Redis::del(Redis::keys('yourFavoriteBanner_*')); }
            if(Redis::keys('popularDisneyBanner_*') != null){  Redis::del(Redis::keys('popularDisneyBanner_*')); }
            if(Redis::keys('esportBanner_*') != null){  Redis::del(Redis::keys('esportBanner_*')); }
            if(Redis::keys('onMobileBanner_*') != null){  Redis::del(Redis::keys('onMobileBanner_*')); }

            $gamescreen = new $this->model;
            $allColumns = $gamescreen->gettableColumns($gamescreen->gettable());
            foreach ($inputs as $key => $value) {
               
                if (in_array($key, $allColumns)) {
                    $gamescreen->$key = $value;
                }
            }
           
            $save = $gamescreen->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'GameScreen']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'GameScreen']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'GameScreen Name']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'GameScreen Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $gamescreen)
    {
        try {

            if(Redis::keys('gameRailSequence_*') != null){  Redis::del(Redis::keys('gameRailSequence_*')); } 
            if(Redis::keys('upperGameBanner_*') != null){  Redis::del(Redis::keys('upperGameBanner_*')); }
            if(Redis::keys('featuredGameBanner_*') != null){  Redis::del(Redis::keys('featuredGameBanner_*')); } 
            if(Redis::keys('trendingNowGameBanner_*') != null){  Redis::del(Redis::keys('trendingNowGameBanner_*')); } 
            if(Redis::keys('socialGamingBanner_*') != null){  Redis::del(Redis::keys('socialGamingBanner_*')); } 
            if(Redis::keys('bottomGameBanner_*') != null){  Redis::del(Redis::keys('bottomGameBanner_*')); } 
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); } 
            if(Redis::keys('yourFavoriteBanner_*') != null){  Redis::del(Redis::keys('yourFavoriteBanner_*')); }
            if(Redis::keys('popularDisneyBanner_*') != null){  Redis::del(Redis::keys('popularDisneyBanner_*')); }
            if(Redis::keys('esportBanner_*') != null){  Redis::del(Redis::keys('esportBanner_*')); }
            if(Redis::keys('onMobileBanner_*') != null){  Redis::del(Redis::keys('onMobileBanner_*')); }

            foreach ($inputs as $key => $value) {
                if (isset($gamescreen->$key)) {
                    $gamescreen->$key = $value;
                }
            }
           
            $save = $gamescreen->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'GameScreen Name']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'GameScreen Name']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'ilent Ota']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'GameScreen Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            if(Redis::keys('gameRailSequence_*') != null){  Redis::del(Redis::keys('gameRailSequence_*')); } 
            if(Redis::keys('upperGameBanner_*') != null){  Redis::del(Redis::keys('upperGameBanner_*')); }
            if(Redis::keys('featuredGameBanner_*') != null){  Redis::del(Redis::keys('featuredGameBanner_*')); } 
            if(Redis::keys('trendingNowGameBanner_*') != null){  Redis::del(Redis::keys('trendingNowGameBanner_*')); } 
            if(Redis::keys('socialGamingBanner_*') != null){  Redis::del(Redis::keys('socialGamingBanner_*')); } 
            if(Redis::keys('bottomGameBanner_*') != null){  Redis::del(Redis::keys('bottomGameBanner_*')); } 
            if(Redis::keys('dashboardGameBanner_*') != null){  Redis::del(Redis::keys('dashboardGameBanner_*')); } 
            if(Redis::keys('yourFavoriteBanner_*') != null){  Redis::del(Redis::keys('yourFavoriteBanner_*')); }
            if(Redis::keys('popularDisneyBanner_*') != null){  Redis::del(Redis::keys('popularDisneyBanner_*')); }
            if(Redis::keys('esportBanner_*') != null){  Redis::del(Redis::keys('esportBanner_*')); }
            if(Redis::keys('onMobileBanner_*') != null){  Redis::del(Redis::keys('onMobileBanner_*')); }

            $resultStatus = false;
            $id = $inputs['ids'];
            $gamescreen = GameScreen::find($id);
    
            if (!empty($gamescreen)) {
               
                $gamescreen->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'GameScreen Name']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'GameScreen Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
