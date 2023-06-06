<?php
/**
 * The class for handling validation requests from TestimonialsController::deleteAction()
 *
 *
 * @author Sachin S. <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Gallery;
use Modules\Admin\Services\Helper\ImageHelper;
use Exception;
use Route;
use Auth;
use Log;
use Cache;
use URL;
use File;
use DB;
use PDO;

class GalleryRepository extends BaseRepository {

    /**
     * Create a new TestimonialsRepository instance.
     *
     * @param  Modules\Admin\Models\Testimonials $model
     * @return void
     */
    public function __construct(Gallery $gallery) {
        $this->model = $gallery;
    }

    /**
     * Get a listing of the resource.
     *
     * @return Response
     */
    public function data($params = []) {
        //Cache::tags('home')->flush();
        $cacheKey = str_replace(['\\'], [''], __METHOD__) . ':' . md5(json_encode($params));
        $response = Cache::tags(Gallery::table())->remember($cacheKey, $this->ttlCache, function() {
            return Gallery::select('*')->orderBy('updated_at', 'DESC')->get();
        });

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Form data posted from ajax $inputs
     * @return $result array with status and message elements
     */
    public function create($inputs, $user_id = null) {
        try {
            $gallery = new $this->model;

            $allColumns = $gallery->getTableColumns($gallery->getTable());

            foreach ($inputs as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $gallery->$key = $value;
                }
            }

            $save = $gallery->save();

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $gallery);

            if ($save) {
                $this->flushAllCacheKeys();

                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.added', ['name' => trans('Gallery')]);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => trans('Gallery')]);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-added', ['name' => trans('Gallery')]) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-added', ['name' => trans('Gallery')]), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }            Cache::tags('home')->flush();
    }

    /**
     * Update an testimonials.
     *
     * @param  Form data posted from ajax $inputs, Modules\Admin\Models\Testimonials $testimonials
     * @return $result array with status and message elements
     * @return void
     */
    public function update($inputs, $gallery) {
        try {
            foreach ($inputs as $key => $value) {
                if (isset($gallery->$key)) {
                    $gallery->$key = $value;
                }
            }
            
            $gallery->image_alt_text = $inputs['image_alt_text'];
            
            $save = $gallery->save();

            /* Function to upload Offer category Icon */
            $this->updateCategoryIcon($inputs, $gallery);

            if ($save) {
                $this->flushAllCacheKeys();
                $response['status'] = 'success';
                $response['message'] = trans('admin::messages.updated', ['name' => trans('Gallery')]);
            } else {
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-updated', ['name' => trans('Gallery')]);
            }

            return $response;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-updated', ['name' => trans('Gallery')]) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-updated', ['name' => trans('Gallery')]), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

            return $response;
        }
    }

    /**
     * Delete actions on testimonials
     *
     * @param  int  $status
     * @return int
     */
    public function deleteAction($inputs) {
        try {
            Cache::tags('home')->flush();
            $resultStatus = false;

            $id = $inputs['ids'];

            $gallery = Gallery::find($id);
            if (!empty($gallery)) {
                $gallery->delete();
                $resultStatus = true;
                $this->flushAllCacheKeys();
            }

            return $resultStatus;
        } catch (Exception $e) {
            $exceptionDetails = $e->getMessage();
            $response['status'] = 'error';
            $response['message'] = trans('admin::messages.not-deleted', ['name' => trans('Gallery')]) . "<br /><b> Error Details</b> - " . $exceptionDetails;
            Log::error(trans('admin::messages.not-deleted', ['name' => trans('Gallery')]), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($inputs, $gallery) {
        Cache::tags('home')->flush();
        if (!empty($inputs['image'])) {
            //unlink old file
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getGalleryUploadFolder($gallery->id) . $inputs['previous_image']);
            }
            $gallery->image = ImageHelper::uploadGalleryS3($inputs['image'], $gallery);

            $gallery->save();
        } else if ($inputs['remove'] == 'remove') {
            $gallery->image = '';
            if (!empty($inputs['previous_image'])) {
                File::Delete(public_path() . ImageHelper::getGalleryUploadFolder($gallery->id) . $inputs['previous_image']);
            }
            $gallery->save();
        } else {
            $gallery->save();
        }
        
        sleep(1);
        
        if (!empty($inputs['thumbnail_image'])) {
            //unlink old file
            if (!empty($inputs['previous_image1'])) {
                File::Delete(public_path() . ImageHelper::getGalleryUploadFolder($gallery->id) . $inputs['previous_image1']);
            }
            $gallery->thumbnail_image = ImageHelper::uploadGalleryS3($inputs['thumbnail_image'], $gallery);

            $gallery->save();
        } else if ($inputs['remove'] == 'remove') {
            $gallery->thumbnail_image = '';
            if (!empty($inputs['previous_image1'])) {
                File::Delete(public_path() . ImageHelper::getGalleryUploadFolder($gallery->id) . $inputs['previous_image1']);
            }
            $gallery->save();
        } else {
            $gallery->save();
        }
        
    }

    public function flushAllCacheKeys() {
        $cacheKey = md5('home');
        Cache::forget($cacheKey);
        Cache::tags('home')->flush();

        //remove entire page cache
        $cacheKey1 = md5('gallery');
        Cache::forget($cacheKey1);

        $pageSlugCacheKey = "page-data-gallery";
        $cacheKey2 = md5($pageSlugCacheKey);
        Cache::forget($cacheKey2);

        $pageSlugCacheKey1 = "page-data-en.page.gallery";
        $cacheKey3 = md5($pageSlugCacheKey1);
        Cache::forget($cacheKey3);

        $pageSlugCacheKey2 = "page-data-ar.page.gallery";
        $cacheKey4 = md5($pageSlugCacheKey2);
        Cache::forget($cacheKey4);

        $pageSlugCacheKey3 = "en.page.gallery";
        $cacheKey5 = md5($pageSlugCacheKey3);
        Cache::forget($cacheKey5);

        $pageSlugCacheKey4 = "ar.page.gallery";
        $cacheKey6 = md5($pageSlugCacheKey4);
        Cache::forget($cacheKey6);
    }

}
