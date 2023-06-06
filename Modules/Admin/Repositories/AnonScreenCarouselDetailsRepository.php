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

use Modules\Admin\Models\AnonScreenDetails;
use Modules\Admin\Models\AnonScreenCarouselDetails;
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

class AnonScreenCarouselDetailsRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(AnonScreenCarouselDetails $anonScreenCarouselDetails)
    {
        $this->model = $anonScreenCarouselDetails;
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
        $response = Cache::tags(AnonScreenCarouselDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return AnonScreenCarouselDetails::select('*')->with('Screens')->orderBy('id')->get();
        });
//        echo 'SS : <pre>';        print_r($response->toArray()); die;
        return $response;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listScreenData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        $response = Cache::tags(AnonScreenDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return AnonScreenDetails::orderBY('id')->lists('screen_id', 'id');
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
            //echo '<pre>'; print_r($inputs); die;
            if($inputs['media_type'] == 'Video' && $inputs['video_url'] != ''){ 
                $inputs['media'] = trim($inputs['video_url']); 
            }
            $redisKeys = Redis::keys('Anon_Screen_Details_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            
            $anonScreenCarouselDetails = new $this->model;
            $allColumns = $anonScreenCarouselDetails->getTableColumns($anonScreenCarouselDetails->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'media' ){
                    $anonScreenCarouselDetails->media = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $anonScreenCarouselDetails->$key = $value;
                    }
                }    
            }
            $save = $anonScreenCarouselDetails->save();
            
            /* Function to upload Offer category Icon */
            if($inputs['media_type'] != 'Video'){
                $this->updateCategoryIcon($inputs, $anonScreenCarouselDetails);
            }

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Anon Screen Carousel Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Anon Screen Carousel Details']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Anon Screen Carousel Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Anon Screen Carousel Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $anonScreenCarouselDetails) {
        Cache::tags('home')->flush();
        if (!empty($inputs['media'])) {
            //unlink old file
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getAnonScreenCarouselDetailsImageUploadFolder($anonScreenCarouselDetails->id) . $inputs['previous_image']);
            }
            // $anonScreenCarouselDetails->media = ImageHelper::uploadAnonScreenCarouselDetailsImage($inputs['media'], $anonScreenCarouselDetails);
            $anonScreenCarouselDetails->media = ImageHelper::uploadAnonScreenCarouselDetailsImageS3($inputs['media'], $anonScreenCarouselDetails);

            $anonScreenCarouselDetails->save();
        } else if ($inputs['remove'] == 'remove') {
            $anonScreenCarouselDetails->media = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getAnonScreenCarouselDetailsImageUploadFolder($anonScreenCarouselDetails->id) . $inputs['previous_image']);
            }
            $anonScreenCarouselDetails->save();
        } else {
            $anonScreenCarouselDetails->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $anonScreenCarouselDetails)
    {
        try {
            
            if($inputs['media_type'] == 'Video' && $inputs['video_url'] != ''){ 
                $inputs['media'] = trim($inputs['video_url']); 
            }
            $redisKeys = Redis::keys('Anon_Screen_Details_*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            }
            
            foreach ($inputs as $key => $value) {
                if (isset($anonScreenCarouselDetails->$key)) {
                    $anonScreenCarouselDetails->$key = $value;
                }
            }
            $save = $anonScreenCarouselDetails->save();

            /* Function to upload Offer category Icon */
            if($inputs['media_type'] != 'Video'){
                $this->updateCategoryIcon($inputs, $anonScreenCarouselDetails);
            }
            
            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Anon Screen Carousel Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Anon Screen Carousel Details']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Anon Screen Carousel Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Anon Screen Carousel Details could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $anonScreenCarouselDetails = AnonScreenCarouselDetails::find($id);
            if (!empty($anonScreenCarouselDetails)) {
                $redisKeys = Redis::keys('Anon_Screen_Details_*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                }
                $anonScreenCarouselDetails->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Anon Screen Carousel Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Anon Screen Carousel Details could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
