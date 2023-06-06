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

use Modules\Admin\Models\Ideatuesday;
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

class IdeatuesdayRepository extends BaseRepository
{

    /**
     * Create a new IdeatuesdayRepository instance.
     *
     * @param  Modules\Admin\Models\Ideatuesday $model
     * @return void
     */
    public function __construct(Ideatuesday $model)
    {
        $this->model = $model;
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Form data posted from ajax $inputs
     * @return $result array with status and message elements
     */
    public function create($inputs)
    {
           /* Function to upload Offer category Icon */
            try {
                
                
                $save = 0;
                foreach($inputs['images'] as $key => $value){
                    $this->updateCategoryIcon($value, $inputs['image_type']);
                    //echo '<pre>'; print_r($value); 
                    $save = 1;
                    sleep(3);
                }
                //die;
                        
                if ($save) {
                    $response['status'] = 'success';
                    if($inputs['image_type'] == 'Redx'){
                        $response['message'] = 'Redx images uploaded successfully';
                    }else if($inputs['image_type'] == 'Brandx'){   
                        $response['message'] = 'Brandx images uploaded successfully';
                    }else if($inputs['image_type'] == 'IdeaTuesday'){   
                        $response['message'] = 'Idea Tuesday images uploaded successfully';
                    }else if($inputs['image_type'] == 'FAQExcelFiles'){   
                        $response['message'] = 'FAQ Excel Files uploaded successfully';
                    }else{
                        $response['message'] = 'Vodafone Tuesday images uploaded successfully';
                    }
                } else {
                    $response['status'] = 'error';
                    if($inputs['image_type'] == 'Redx'){
                        $response['message'] = 'Redx images not uploaded successfully';
                    }else if($inputs['image_type'] == 'Brandx'){   
                        $response['message'] = 'Brandx images not uploaded successfully';
                    }else if($inputs['image_type'] == 'IdeaTuesday'){   
                        $response['message'] = 'Idea Tuesday images not uploaded successfully';
                    }else if($inputs['image_type'] == 'FAQExcelFiles'){   
                        $response['message'] = 'FAQ Excel Files uploaded successfully';
                    }else{
                        $response['message'] = 'Vodafone Tuesday images not uploaded successfully';
                    }
                }
                
                return $response;
            } catch (Exception $e) {
                $exceptionDetails = $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = trans('admin::messages.not-added', ['name' => 'Banner']) . "<br /><b> Error Details</b> - " . $exceptionDetails;
                Log::error(trans('admin::messages.not-added', ['name' => 'Banner']), ['Error Message' => $exceptionDetails, 'Current Action' => Route::getCurrentRoute()->getActionName()]);

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
    public function updateCategoryIcon($fileObj, $imageType) {
        Cache::tags('home')->flush();
        if (!empty($fileObj)) {
            //ImageHelper::uploadIdeaTuesday($fileObj);    
            if($imageType == 'Redx'){
                ImageHelper::uploadRedxS3($fileObj);            
            }else if($imageType == 'Brandx'){        
                ImageHelper::uploadBrandxS3($fileObj);            
            }else if($imageType == 'IdeaTuesday'){        
                ImageHelper::uploadIdeaTuesdayS3($fileObj);            
            }else if($imageType == 'FAQExcelFiles'){        
                ImageHelper::uploadFAQExcelFilesS3($fileObj);            
            }else{
                ImageHelper::uploadVodafoneTuesdayS3($fileObj);
            }
        } 
        return true;
    }

    
}
