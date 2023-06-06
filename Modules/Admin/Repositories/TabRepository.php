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

use Modules\Admin\Models\Tab;
use Exception;
use Route;
use Log;
use Cache;
use Illuminate\Support\Facades\Redis;

class TabRepository extends BaseRepository
{

    /**
     * Create a new RechargeOffersRepository instance.
     *
     * @param  Modules\Admin\Models\RechargeOffers $model
     * @return void
     */
    public function __construct(Tab $Tab)
    {
        $this->model = $Tab;
    }

    /**
     * Get a listing of the resource.
     *
     * @return Response
     */
    public function listTabData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(Tab::table())->remember($cacheKey, $this->ttlCache, function() {
            return Tab::orderBY('id')->where('status',1)->lists('name','name');
        });
      
        return $response;
    }

    public function data($params = [])
    {

        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(Tab::table())->remember($cacheKey, $this->ttlCache, function() {
            return Tab::orderBy('id')->get();
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
           
            $Tab = new $this->model;
            $allColumns = $Tab->getTableColumns($Tab->getTable());
            foreach ($inputs as $key => $value) {
               
                if (in_array($key, $allColumns)) {
                    $Tab->$key = $value;
                }
            }
           
            $save = $Tab->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Tab']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Tab']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Tab Name']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Tab Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $Tab)
    {
        try {
            foreach ($inputs as $key => $value) {
                if (isset($Tab->$key)) {
                    $Tab->$key = $value;
                }
            }
           
            $save = $Tab->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Tab Name']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Tab Name']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'ilent Ota']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Tab Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $Tab = Tab::find($id);
            if (!empty($Tab)) {
               
                $Tab->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Tab Name']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Tab Name']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }
}
