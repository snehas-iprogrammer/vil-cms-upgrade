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

use Modules\Admin\Models\PaymentBanners;
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

class PaymentBannersRepository extends BaseRepository
{

    /**
     * Create a new BannerRepository instance.
     *
     * @param  Modules\Admin\Models\Banner $model
     * @return void
     */
    public function __construct(PaymentBanners $paymentBanners)
    {
        $this->model = $paymentBanners;
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
        $response = Cache::tags(PaymentBanners::table())->remember($cacheKey, $this->ttlCache, function() {
            return PaymentBanners::orderBy('id')->get();
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
            
            $redisKeys = Redis::keys('Payment_Banners*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            $paymentBanners = new $this->model;
            $allColumns = $paymentBanners->getTableColumns($paymentBanners->getTable());
            foreach ($inputs as $key => $value) {
                if($key == 'image' ){
                    $paymentBanners->image = '';
                }else{
                    if (in_array($key, $allColumns)) {
                        $paymentBanners->$key = $value;
                    }
                }    
            }
            $save = $paymentBanners->save();
            
            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $paymentBanners);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Payment Banners']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Payment Banners']);
            }
            
            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Payment Banners']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Payment Banners']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $paymentBanners) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            // if (!empty($inputs['previous_image'])) {
            //     File::Delete(public_path() . ImageHelper::getPaymentBannerUploadFolder($paymentBanners->id) . $inputs['previous_image']);
            // }

            if (isset($inputs['previous_image'])) {
                $delete = ImageHelper::deleteS3File($inputs['previous_image']);
            }
            //$paymentBanners->image = ImageHelper::uploadPaymentBanner($inputs['image'], $paymentBanners);
            $paymentBanners->image = ImageHelper::uploadPaymentBannerS3($inputs['image'], $paymentBanners);

            $paymentBanners->save();
        } else if ($inputs['remove'] == 'remove') {
            $paymentBanners->image = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getPaymentBannerUploadFolder($paymentBanners->id) . $inputs['previous_image']);
            }
            $paymentBanners->save();
        } else {
            $paymentBanners->save();
        }
        return true;
    }

    /**
     * Update an Banner.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Banner $banner
     * @return $result array with status and message elements
     */
    public function update($inputs, $paymentBanners)
    {
        try {
            $redisKeys = Redis::keys('Payment_Banners*');
            if($redisKeys != null){ 
                Redis::del($redisKeys); // delete only pattern match to other_banners_                    
            } 
            foreach ($inputs as $key => $value) {
                if (isset($paymentBanners->$key)) {
                    $paymentBanners->$key = $value;
                }
            }
            
            $save = $paymentBanners->save();

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $paymentBanners);

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Payment Banners']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Payment Banners']);
            }
            
            return $response;
        } catch (Exception $e) {

            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Payment Banners']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Payment Banners could not be updated.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $paymentBannersDetails = PaymentBanners::find($id);
            if (!empty($paymentBannersDetails)) {
                $redisKeys = Redis::keys('Payment_Banners*');
                if($redisKeys != null){ 
                    Redis::del($redisKeys); // delete only pattern match to other_banners_                    
                } 
                $delete = ImageHelper::deleteS3File($paymentBannersDetails['image']);
                $paymentBannersDetails->delete();
                $resultStatus = true;
            }
            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Payment Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error("Payment Banner could not be Deleted.", ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
