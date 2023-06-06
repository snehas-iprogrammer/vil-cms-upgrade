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

use Modules\Admin\Models\UpsellMrpConfigurations;
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

class UpsellMrpConfigurationsRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(UpsellMrpConfigurations $upsellMrpConfigurations)
    {
        $this->model = $upsellMrpConfigurations;
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
        $response = Cache::tags(UpsellMrpConfigurations::table())->remember($cacheKey, $this->ttlCache, function() {
            return UpsellMrpConfigurations::orderBy('id')->get();
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
            if(Redis::keys('New_Recharge_Offers*') != null){  
                Redis::del(Redis::keys('New_Recharge_Offers*'));
            }
            
            if(isset($inputs['circle']) && $inputs['circle'] != NULL){
                $filteredArray = array_unique(array_filter($inputs['circle']));
                $inputs['circle'] = implode(',', $filteredArray);
            }

            if(isset($inputs['app_version']) && !empty($inputs['app_version'])){
                $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
                $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            }

            
            $upsellMrpConfigurations = new $this->model;
            $allColumns = $upsellMrpConfigurations->getTableColumns($upsellMrpConfigurations->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $upsellMrpConfigurations->image = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $upsellMrpConfigurations->$key = $value;
                    }
                }    
            }
            $save = $upsellMrpConfigurations->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $upsellMrpConfigurations);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Upsell Mrp Configuration']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Upsell Mrp Configuration']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Upsell Mrp Configuration']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Upsell Mrp Configuration']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $upsellMrpConfigurations) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            // if (!empty($inputs['previous_image'])) {
            //     File::Delete(public_path() . ImageHelper::getUpsellMrpConfigurationImageUploadFolder($upsellMrpConfigurations->id) . $inputs['previous_image']);
            // }

            if (isset($inputs['previous_image'])) {
                $delete = ImageHelper::deleteS3File($inputs['previous_image']);
            }

            //$upsellMrpConfigurations->image = ImageHelper::uploadUpsellMrpConfigurationImage($inputs['image'], $upsellMrpConfigurations);
            $upsellMrpConfigurations->image = ImageHelper::uploadUpsellMrpConfigurationImageS3($inputs['image'], $upsellMrpConfigurations);

            $upsellMrpConfigurations->save();
        } else if ($inputs['remove'] == 'remove') {
            $upsellMrpConfigurations->image = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getUpsellMrpConfigurationImageUploadFolder($upsellMrpConfigurations->id) . $inputs['previous_image']);
            }
            $upsellMrpConfigurations->save();
        } else {
            $upsellMrpConfigurations->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $upsellMrpConfigurations)
    {
        try {
            if(Redis::keys('New_Recharge_Offers*') != null){  
                Redis::del(Redis::keys('New_Recharge_Offers*'));
            }

            if(isset($inputs['app_version']) && !empty($inputs['app_version'])){
                $filteredAppVersionArray = array_unique(array_filter($inputs['app_version']));
                $inputs['app_version'] = implode(',', $filteredAppVersionArray);
            }
            
            if(isset($inputs['circle']) && $inputs['circle'] != NULL){
                $filteredArray = array_unique(array_filter($inputs['circle']));
                $inputs['circle'] = implode(',', $filteredArray);
            }else{
                $inputs['circle'] = '';
            }
            
            foreach ($inputs as $key => $value) {
                if (isset($upsellMrpConfigurations->$key)) {
                    $upsellMrpConfigurations->$key = $value;
                }
            }

            $save = $upsellMrpConfigurations->save();

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $upsellMrpConfigurations);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Upsell Mrp Configuration']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Upsell Mrp Configuration']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Upsell Mrp Configuration']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Upsell Mrp Configuration could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $upsellMrpConfigurationsDetails = UpsellMrpConfigurations::find($id);
            if (!empty($upsellMrpConfigurationsDetails)) {
                if(Redis::keys('New_Recharge_Offers*') != null){  
                    Redis::del(Redis::keys('New_Recharge_Offers*'));
                }
                
                $delete = ImageHelper::deleteS3File($upsellMrpConfigurationsDetails['image']);
                $upsellMrpConfigurationsDetails->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Upsell Mrp Configurations']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Upsell Mrp Configuration could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
