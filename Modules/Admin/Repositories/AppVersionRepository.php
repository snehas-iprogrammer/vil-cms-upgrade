<?php
/**
 * The repository class for managing AppVersion actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\AppVersion;
use Illuminate\Support\Facades\Redis;
use Exception;
use Route;
use Log;
use Cache;

class AppVersionRepository extends BaseRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  Modules\Admin\Models\AppVersion $AppVersion
     * @return void
     */
    public function __construct(AppVersion $appVersion)
    {
        $this->model = $appVersion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function data($params = [])
    {

        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(AppVersion::table())->remember($cacheKey, $this->ttlCache, function() {
            return AppVersion::orderBy('app_version')->get();
        });

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listAppVersionData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(AppVersion::table())->remember($cacheKey, $this->ttlCache, function() {
            return AppVersion::orderBY('app_version')->where('status',1)->pluck('app_version','app_version');
        });
      
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Form data posted from ajax $inputs
     * @return $result array with status and message elements
     */
    public function store($inputs)
    {
        try {
            $appversion = new $this->model;
            $allColumns = $appversion->getTableColumns($appversion->getTable());
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $appversion->$key = trim($value);
                }
            }

            $save = $appversion->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => trans('admin::controller/appversion.appversion')]);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => trans('admin::controller/appversion.appversion')]);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => trans('admin::controller/appversion.appversion')]) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => trans('admin::controller/appversion.appversion')]), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
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
            $appVersion = new $this->model;
            Redis::del($inputs['app_version']);
            $allColumns = $appVersion->getTableColumns($appVersion->getTable());
            //echo '<pre>'; print_r($allColumns); die;
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $appVersion->$key = trim($value);
                }
            }
            
            $save = $appVersion->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'App version']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'App version']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'App version']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'App version']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $appVersion)
    {
        try {            
            Redis::del($inputs['app_version']);
            foreach ($inputs as $key => $value) {
                if (isset($appVersion->$key)) {
                    $appVersion->$key = trim($value);
                }
            }
            
            $save = $appVersion->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'App version']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'App version']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'App version']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'App version']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $appVersion = AppVersion::find($id);
            if (!empty($appVersion)) {            
                Redis::flushDB();
                $appVersion->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'App version']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'App version']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    
}
