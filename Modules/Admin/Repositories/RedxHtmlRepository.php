<?php
/**
 * The repository class for managing RedxHtml actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Repositories;

use Modules\Admin\Models\RedxHtml;
use Illuminate\Support\Facades\Redis;
use Exception;
use Route;
use Log;
use Cache;

class RedxHtmlRepository extends BaseRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  Modules\Admin\Models\RedxHtml $RedxHtml
     * @return void
     */
    public function __construct(RedxHtml $redxHtml)
    {
        $this->model = $redxHtml;
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
        $response = Cache::tags(RedxHtml::table())->remember($cacheKey, $this->ttlCache, function() {
            return RedxHtml::orderBy('redx_html')->get();
        });

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listRedxHtmlData()
    {
        $cacheKey = str_replace(['\\'], [''], __METHOD__);
        //Cache::tags not suppport with files and Database
        $response = Cache::tags(RedxHtml::table())->remember($cacheKey, $this->ttlCache, function() {
            return RedxHtml::orderBY('redx_html')->lists('redx_html');
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
            $redxHtml = new $this->model;
            $allColumns = $redxHtml->getTableColumns($redxHtml->getTable());
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $redxHtml->$key = $value;
                }
            }

            $save = $redxHtml->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => trans('admin::controller/redxHtml.redxHtml')]);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => trans('admin::controller/redxHtml.redxHtml')]);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => trans('admin::controller/redxHtml.redxHtml')]) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => trans('admin::controller/redxHtml.redxHtml')]), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
            $redxHtml = new $this->model;

            $allColumns = $redxHtml->getTableColumns($redxHtml->getTable());
            //echo '<pre>'; print_r($allColumns); die;
            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $redxHtml->$key = $value;
                }
            }
            
            $save = $redxHtml->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => 'Redx Html']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Redx Html']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => 'Redx Html']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => 'Redx Html']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function update($inputs, $redxHtml)
    {
        try {

            foreach ($inputs as $key => $value) {
                if (isset($redxHtml->$key)) {
                    $redxHtml->$key = $value;
                }
            }
            Redis::del('Redx_Html');
            $save = $redxHtml->save();

            if ($save) {
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => 'Redx Html']);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => 'Redx Html']);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => 'Redx Html']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => 'Redx Html']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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

            $redxHtml = RedxHtml::find($id);
            if (!empty($redxHtml)) {
                $redxHtml->delete();
                $resultStatus = true;
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => 'Redx Html']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => 'Redx Html']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    
}
