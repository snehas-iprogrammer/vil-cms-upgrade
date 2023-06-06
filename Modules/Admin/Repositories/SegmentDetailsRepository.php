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

use Modules\Admin\Models\SegmentDetails;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class SegmentDetailsRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(SegmentDetails $segmentDetails)
    {
        $this->model = $segmentDetails;
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
        $response = Cache::tags(SegmentDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return SegmentDetails::select([
                    'id', 'segment_name', 'route_name', 'referred_json', 'status'
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
        $response = Cache::tags(SegmentDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return SegmentDetails::orderBY('id')->lists('segment_name', 'id');
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
        $response = Cache::tags(SegmentDetails::table())->remember($cacheKey, $this->ttlCache, function() {
            return SegmentDetails::orderBY('id')->lists('segment_name', 'id');
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
            $segmentDetails = new $this->model;
            $allColumns = $segmentDetails->getTableColumns($segmentDetails->getTable());
            $inputs['referred_json'] = trim($inputs['referred_json']);
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $segmentDetails->$key = $value;
                }
            }
            $segmentDetails->status = isset($inputs['status']) ? $inputs['status'] : 0;
            Redis::del('New_Segment_Details_'.$inputs['route_name']);
            $save = $segmentDetails->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Segment Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Segment Details']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Segment Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Segment Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $segmentDetails)
    {
        try {
            $inputs['referred_json'] = trim($inputs['referred_json']);
            foreach ($inputs as $key => $value) {
                if (isset($segmentDetails->$key)) {
                    $segmentDetails->$key = $value;
                }
            }
            $segmentDetails->status = isset($inputs['status']) ? $inputs['status'] : 0;
            Redis::del('New_Segment_Details_'.$inputs['route_name']);
            $save = $segmentDetails->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Segment Details']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Segment Details']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Segment Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Segment Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $segmentDetails = SegmentDetails::find($id);
            if (!empty($segmentDetails)) {
                Redis::del('New_Segment_Details_'.$segmentDetails->route_name);
                $segmentDetails->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Segment Details']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Segment Details']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
