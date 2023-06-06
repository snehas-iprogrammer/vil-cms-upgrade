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

use Modules\Admin\Models\DigitalOnboarding;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class DigitalOnboardingRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(DigitalOnboarding $digitalOnboarding)
    {
        $this->model = $digitalOnboarding;
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
        $response = Cache::tags(DigitalOnboarding::table())->remember($cacheKey, $this->ttlCache, function() {
            return DigitalOnboarding::select([
                    'id', 'prepaid_circles', 'postpaid_circles', 'status'
                ])->orderBy('id')->get();
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
            $digitalOnboarding = new $this->model;
            $allColumns = $digitalOnboarding->getTableColumns($digitalOnboarding->getTable());
            $inputs['prepaid_circles'] = trim($inputs['prepaid_circles']);
            $inputs['postpaid_circles'] = trim($inputs['postpaid_circles']);
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $digitalOnboarding->$key = $value;
                }
            }
            $digitalOnboarding->status = isset($inputs['status']) ? $inputs['status'] : 0;
            Redis::del('Digital_Onboarding_Segments');
            $save = $digitalOnboarding->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Digital Onboarding']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Digital Onboarding']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Digital Onboarding']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Digital Onboarding']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $digitalOnboarding)
    {
        try {
            $inputs['prepaid_circles'] = trim($inputs['prepaid_circles']);
            $inputs['postpaid_circles'] = trim($inputs['postpaid_circles']);
            foreach ($inputs as $key => $value) {
                if (isset($digitalOnboarding->$key)) {
                    $digitalOnboarding->$key = $value;
                }
            }
            $digitalOnboarding->status = isset($inputs['status']) ? $inputs['status'] : 0;
            Redis::del('Digital_Onboarding_Segments');
            $save = $digitalOnboarding->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Digital Onboarding']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Digital Onboarding']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Digital Onboarding']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Digital Onboarding']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $digitalOnboarding = DigitalOnboarding::find($id);
            if (!empty($digitalOnboarding)) {
                Redis::del('Digital_Onboarding_Segments');
                $digitalOnboarding->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Digital Onboarding']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Digital Onboarding']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
