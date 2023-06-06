<?php
/**
 * The class for handling validation requests from TestimonialsController::deleteAction()
 *
 *
 * @author Sachin S. <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Services\Helper;

use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManagerStatic as Image;
use Guzzle\Http\EntityBody;
use File;
use Config;
use Storage;
use DB;
use Route;
use App;

class ImageHelper {

    /**
     * upload user avatar
     * @return String
     */
    public static function uploadUserAvatar($fileObj, $user = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'avatar' . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getUserUploadFolder($user->id);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }

    /**
     * get user avatar upload folder path
     * @return String
     */
    public static function getUserUploadFolder($userId = '') {
        if (empty($userId)) {
            $userId = \Auth::user()->id;
        }
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    /**
     * check and create directory
     */
    public static function checkDirectory($dirPath = '') {
        if (!\File::exists($dirPath)) {
            \File::makeDirectory($dirPath, 0775, true);
        }
    }

    /**
     * get default image link
     */
    public static function getDefaultImageLink() {
        return \URL::asset('images/default-user-icon-profile.png ');
    }

    /**
     * get default image link
     */
    public static function getDefaultCategoryIconLink() {
        return \URL::asset('images/default-offer-category-icon.png');
    }

    /**
     * get default image
     */
    public static function getDefaultImage($class = 'img-thumbnail img-responsive') {
        return \Form::image(self::getDefaultImageLink(), ' ', ['class' => $class]);
    }

    /**
     * check and create directory
     */
    public static function getUserAvatar($userId = '', $avatar = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultImageLink();
        if (!empty($userId) && empty($avatar)) {
            $user = DB::table('admins')->select('avatar')->where('id', $userId)->first();
            $avatar = $user->avatar;
        }
        if (!empty($avatar)) {

            return \HTML::image(\URL::asset(self::getUserUploadFolder($userId) . $avatar), $avatar, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }

    /**
     * upload offer category icon
     * @return String
     */
    public static function uploadOfferCategoryIcon($fileObj, $offercategory = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'offer-category-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getOfferCategoryUploadFolder($offercategory->id);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }

    /**
     * upload offer category icon
     * @return String
     */
    public static function uploadTestimonialIcon($fileObj, $offercategory = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'testimonial-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getTestimonialUploadFolder($offercategory->id);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }

    public static function uploadGallery($fileObj, $offercategory = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'gallery-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getGalleryUploadFolder($offercategory->id);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }
    
    public static function uploadGalleryS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'gallery-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getGalleryUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    //banner images upload on s3 bucket
    public static function uploadBannerS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'banner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getBannerUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;
            if($fileObj->getClientOriginalExtension() === 'svg'){
                $s3->getDriver()->put($s3filePath, file_get_contents($fileObj->getPathName()), [ 'visibility' => 'public', 'ContentType' => 'image/svg+xml']);
            }else{
                $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadPaymentBannerS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'paymentbanner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getPaymentBannerUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadUpsellMrpConfigurationImageS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'upsell-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getUpsellMrpConfigurationImageUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadAnonScreenCarouselDetailsImageS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'anonscreencarousel-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();  // $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getAnonScreenCarouselDetailsImageUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            if($fileObj->getClientOriginalExtension() === 'svg'){
                $s3->getDriver()->put($s3filePath, file_get_contents($fileObj->getPathName()), [ 'visibility' => 'public', 'ContentType' => 'image/svg+xml']);
            }else{
                $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadDashboardBannersImageS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getDashboardBannersImageUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadOtherBannerS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'otherbanner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getOtherBannerUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;
            if($fileObj->getClientOriginalExtension() === 'svg'){
                $s3->getDriver()->put($s3filePath, file_get_contents($fileObj->getPathName()), [ 'visibility' => 'public', 'ContentType' => 'image/svg+xml']);
            }else{
                $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadQuickLinksS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'quicklink-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getQuickLinksUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    //Idea tuesday images upload on s3 bucket
    public static function uploadIdeaTuesdayS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                // $fileName = 'banner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
		        $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getIdeaTuesdayUploadFolder(1);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    //Redx images upload on s3 bucket
    public static function uploadRedxS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                // $fileName = 'banner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
		        $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getRedxUploadFolder(1);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    //Brandx images upload on s3 bucket
    public static function uploadBrandxS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getBrandxUploadFolder(1);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    //Vodafone tuesday images upload on s3 bucket
    public static function uploadVodafoneTuesdayS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                // $fileName = 'banner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
		        $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getVodafoneTuesdayUploadFolder(1);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    //FAQ Excel Files upload on s3 bucket
    public static function uploadFAQExcelFilesS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                // $fileName = 'banner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
		        $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getFAQExcelFilesUploadFolder(1);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }
    
    public static function uploadIdeaTuesday($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = $fileObj->getClientOriginalName();
        }
        $path = self::getIdeaTuesdayUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }
	
    public static function getBannerImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset(self::getGalleryUploadFolder($offercategory) . $category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getPaymentBannerImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset($category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getUpsellMrpConfigurationImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset($category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getAnonScreenCarouselDetailsImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset($category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getDashboardBannersImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset($category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getOtherBannerImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset($category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getQuickLinksImagePath($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive')
    {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset($category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
	    
    public static function uploadBanner($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'banner-' . $pageBanner->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getBannerUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }
    
    public static function uploadPaymentBanner($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = $fileObj->getClientOriginalName();
        }  
        $path = self::getPaymentBannerUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);
        $finalPath = $path. ''.$fileName;
        return $finalPath;
    }
    
    public static function uploadUpsellMrpConfigurationImage($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = $fileObj->getClientOriginalName();
        }  
        $path = self::getUpsellMrpConfigurationImageUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);
        $finalPath = $path. ''.$fileName;
        return $finalPath;
    }
    
    public static function uploadAnonScreenCarouselDetailsImage($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'anonscreencarousel-' . $pageBanner->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension(); // $fileObj->getClientOriginalName();
        }  
        $path = self::getAnonScreenCarouselDetailsImageUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);
        $finalPath = $path. ''.$fileName;
        return $finalPath;
    }
    
    public static function uploadDashboardBannersImage($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = $fileObj->getClientOriginalName();
        }  
        $path = self::getDashboardBannersImageUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);
        $finalPath = $path. ''.$fileName;
        return $finalPath;
    }
    
    public static function uploadOtherBanner($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = $fileObj->getClientOriginalName();
        }        
        $path = self::getOtherBannerUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);
        $finalPath = $path. ''.$fileName;
        return $finalPath;
    }
    
    public static function uploadQuickLinks($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = $fileObj->getClientOriginalName();
        }        
        $path = self::getQuickLinksUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);
        $finalPath = $path. ''.$fileName;
        return $finalPath;
    }
    
    public static function uploadImages($fileObj, $pageBanner = '', $fileName = '') {
        $fileExtension = 'jpg';
        if (empty($fileName)) {
            $fileName = 'image-' . time() . '.' . $fileExtension;
        }
        $image = Image::make($fileObj)->encode('jpg')->save($fileName);
        $path = self::getImageUploadFolder($pageBanner);
        $image->move(public_path() . $path, $fileName);

        return $fileName;
    }
    

    public static function getGalleryUploadFolder($offercategory = '') {
        $offer_category_folder = 'gallery';
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getBannerUploadFolder($offercategory = '') {
        $offer_category_folder = 'appimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getPaymentBannerUploadFolder($offercategory = '') {
        $offer_category_folder = 'paymentbannerimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getUpsellMrpConfigurationImageUploadFolder($offercategory = '') {
        $offer_category_folder = 'upsellmrpconfigurationimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getAnonScreenCarouselDetailsImageUploadFolder($offercategory = '') {
        $offer_category_folder = 'anonscreencarouselmedia';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getDashboardBannersImageUploadFolder($offercategory = '') {
        $offer_category_folder = 'dashboardbannerimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getOtherBannerUploadFolder($offercategory = '') {
        $offer_category_folder = 'appotherimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getQuickLinksUploadFolder($offercategory = '') {
        $offer_category_folder = 'quicklinkimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    
    public static function getImageUploadFolder($offercategory = '') {
        $offer_category_folder = 'ideatuesdayimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    public static function getIdeaTuesdayUploadFolder($offercategory = '') {
        $offer_category_folder = 'ideatuesdayimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    public static function getVodafoneTuesdayUploadFolder($offercategory = '') {
        $offer_category_folder = 'vodafonetuesdayimages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    public static function getFAQExcelFilesUploadFolder($offercategory = '') {
        $offer_category_folder = 'faqexcelfiles';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    public static function getRedxUploadFolder($offercategory = '') {
        $offer_category_folder = 'redximages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }
    public static function getBrandxUploadFolder($offercategory = '') {
        $offer_category_folder = 'brandximages';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    public static function getGalleryImg($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {
            $dynamicUrl = config('app.assets_url'). $category_icon;
            //return \HTML::image(\URL::asset(self::getGalleryUploadFolder($offercategory) . $category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
            return \HTML::image($dynamicUrl, $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }
    
    public static function getBannerImg($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {

            return \HTML::image(\URL::asset(self::getBannerUploadFolder($offercategory) . $category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
        
        
        
    }

    /**
     * get offer category icon upload folder path
     * @return String
     */
    public static function getTestimonialUploadFolder($offercategory = '') {
        $offer_category_folder = 'testimonial';
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    /**
     * upload blog
     * @return String
     */
    public static function uploadBlogIcon($fileObj, $offercategory = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'blog-' . $offercategory->id . '-' . time() . '_' . $fileObj->getClientOriginalName();
        }
        $path = self::getBlogUploadFolder($offercategory->id);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }

    /**
     * upload multiple images for blog
     * @return String
     */
    public static function uploadBlogIcons($inputs, $fileObj, $offercategory = '', $fileName = '') {
        //dd($inputs,$fileObj);
        $path = self::getBlogUploadFolder($offercategory->id);
        $images_name = '';$allFiles = '';

        foreach ($fileObj as $key => $value) {
            if (!empty($fileObj[$key])) {
                $fileName = 'blog-' . $offercategory->id . '_' . $key . '-' . $fileObj[$key]->getClientOriginalName();
                $fileObj[$key]->move(public_path() . $path, $fileName);
                $allFiles = trim($allFiles . "," . $fileName, ",");
            }
        }
        if (empty($inputs['previous_feature_image']) && isset($fileObj[0])) {
            $images_name = trim($images_name . "," . $fileName, ",");
        } else if (!empty($inputs['previous_feature_image']) && isset($fileObj[0])) {
            $images_name = trim($images_name . "," . $fileName, ",");
        } else if (!empty($inputs['previous_feature_image']) && empty($fileObj[0])) {
            $images_name = trim($images_name . "," . $inputs['previous_feature_image'], ",");
        }else{
            $images_name = 'null';
        }

        if (empty($inputs['previous_feature_image2']) && isset($fileObj[1])) {
            $images_name = trim($images_name . "," . $fileName, ",");
        } else if (!empty($inputs['previous_feature_image2']) && isset($fileObj[1])) {
            $images_name = trim($images_name . "," . $fileName, ",");
        } else if (!empty($inputs['previous_feature_image2']) && empty($fileObj[1])) {
            $images_name = trim($images_name . "," . $inputs['previous_feature_image2'], ",");
        }else{
            $images_name = trim($images_name . ",null");
        }

        if (empty($inputs['previous_feature_image3']) && isset($fileObj[2])) {
            $images_name = trim($images_name . "," . $fileName, ",");
        } else if (!empty($inputs['previous_feature_image3']) && isset($fileObj[2])) {
            $images_name = trim($images_name . "," . $fileName, ",");
        }else if (!empty($inputs['previous_feature_image3']) && empty($fileObj[2])) {
            $images_name = trim($images_name . "," . $inputs['previous_feature_image3'], ",");
        } else {
            $images_name = trim($images_name . ",null");
        }
        //all files uploaded at a time
        if((isset($fileObj[0])) && (isset($fileObj[1])) && (isset($fileObj[2])))
                {
            $images_name = $allFiles;
        }

        return $images_name;
    }

    /**
     * get blog icon upload folder path
     * @return String
     */
    public static function getBlogUploadFolder($offercategory = '') {
        $offer_category_folder = 'blog';
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    /**
     * get offer category icon upload folder path
     * @return String
     */
    public static function getOfferCategoryUploadFolder($offercategory = '') {
        $offer_category_folder = 'offer-category';
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    /**
     * check and create directory
     */
    public static function getOfferCategoryIcon($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultCategoryIconLink();
        if (!empty($category_icon)) {

            return \HTML::image(\URL::asset(self::getOfferCategoryUploadFolder($offercategory) . $category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }

    /**
     * check and create directory
     */
    public static function getTestimonialIcon($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {

            return \HTML::image(\URL::asset(self::getTestimonialUploadFolder($offercategory) . $category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }

    /**
     * check and create directory
     */
    public static function getBlogIcon($offercategory = '', $category_icon = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultImageLink();
        if (!empty($category_icon)) {

            return \HTML::image(\URL::asset(self::getBlogUploadFolder($offercategory) . $category_icon), $category_icon, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }

    /**
     * get page banner image upload folder path
     * @return String
     */
    public static function getPageBannerUploadFolder($pagebanner = '') {
        $page_banner_folder = 'page-banners';
        if (!empty($pagebanner->page_id)) {
            $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $page_banner_folder . DIRECTORY_SEPARATOR . $pagebanner->page_id;
            self::checkDirectory(public_path() . $path);
        } else {
            $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $page_banner_folder . DIRECTORY_SEPARATOR;
        }
        return $path;
    }

    /**
     * upload Page Banner Image
     * @return String
     */
    public static function uploadPageBannerImage($fileObj, $pageBanner = '', $fileName = '') {
        if (empty($fileName)) {
            $fileName = 'page-banner-' . $pageBanner->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getPageBannerUploadFolder($pageBanner);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }

    /**
     * check and create directory
     */
    public static function getOfferBannerImage($offerbanner = '', $banner_image = '', $class = 'img-thumbnail img-responsive') {
        $default = self::getDefaultImageLink();
        if (!empty($banner_image)) {

            return \HTML::image(\URL::asset(self::getOfferAllImagesUploadFolder('offer-banner') . $banner_image), $banner_image, ['class' => $class, 'onerror' => 'this.src="' . $default . '"']);
        }

        return \HTML::image($default, ' ', ['class' => $class]);
    }

    /**
     * upload offer image
     * @return String
     */
    public static function uploadOfferAllImages($fileObj, $offerimage, $imgFileName) {
        $fileName = $imgFileName . '-' . $offerimage->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        $path = self::getOfferAllImagesUploadFolder($imgFileName);
        $fileObj->move(public_path() . $path, $fileName);
        return $fileName;
    }

    /**
     * get offer image upload folder path
     * @return String
     */
    public static function getOfferAllImagesUploadFolder($imgFilePath) {
        $offer_folder = 'offers/' . $imgFilePath;
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $offer_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    /**
     * upload Page OG image
     * @return String
     */
    public static function uploadPageOgImage($fileObj, $pageimage, $imgFileName) {
        $fileName = $imgFileName . '-' . $pageimage->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        $path = self::getPageOgImageUploadFolder($imgFileName);
        $fileObj->move(public_path() . $path, $fileName);
        return $fileName;
    }

    /**
     * get offer image upload folder path
     * @return String
     */
    public static function getPageOgImageUploadFolder($imgFilePath) {
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $imgFilePath . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    /**
     * upload user details avatar
     * @return String
     */
    public static function uploadUserDetailsAvatar($fileObj, $userId = '', $fileName = '', $flag) {
        if (empty($fileName)) {
            $fileName = $flag . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getUserDetailsUploadFolder($userId);
        $fileObj->move(public_path() . $path, $fileName);

        return $fileName;
    }

    /**
     * get user details avatar upload folder path
     * @return String
     */
    public static function getUserDetailsUploadFolder($userId) {
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);
        return $path;
    }

    /**
     * upload ngo logo
     * @return String
     */
    public static function uploadNgoLogo($fileObj, $userId = '', $fileName = '', $flag) {
        if (empty($fileName)) {
            $fileName = $flag . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getNgoLogoUploadFolder($userId);
        $fileObj->move(public_path() . $path, $fileName);
        return $fileName;
    }
    
    /**
     * upload ngo logo
     * @return String
     */
    public static function uploadCorporateLogo($fileObj, $userId = '', $fileName = '', $flag) {
        if (empty($fileName)) {
            $fileName = $flag . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getCorporateLogoUploadFolder($userId);
        $fileObj->move(public_path() . $path, $fileName);
        return $fileName;
    }

    /**
     * get ngo logo upload folder path
     * @return String
     */
    public static function getNgoLogoUploadFolder($userId) {
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'ngo_logo' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);
        return $path;
    }
    
    /**
     * get ngo logo upload folder path
     * @return String
     */
    public static function getCorporateLogoUploadFolder($userId) {
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'corporate_logo' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);
        return $path;
    }

    /**
     * upload ngo logo
     * @return String
     */
    public static function uploadNgoBanner($fileObj, $userId = '', $fileName = '', $flag) {
        if (empty($fileName)) {
            $fileName = $flag . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getNgoBannerUploadFolder($userId);
        $fileObj->move(public_path() . $path, $fileName);
        return $fileName;
    }
    
    /**
     * upload ngo logo
     * @return String
     */
    public static function uploadCorporateBanner($fileObj, $userId = '', $fileName = '', $flag) {
        if (empty($fileName)) {
            $fileName = $flag . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
        }
        $path = self::getCorporateBannerUploadFolder($userId);
        $fileObj->move(public_path() . $path, $fileName);
        return $fileName;
    }

    /**
     * get ngo logo upload folder path
     * @return String
     */
    public static function getNgoBannerUploadFolder($userId) {
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'ngo_banner' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);
        return $path;
    }
    
    /**
     * get ngo logo upload folder path
     * @return String
     */
    public static function getCorporateBannerUploadFolder($userId) {
        $path = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'corporate_banner' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);
        return $path;
    }

    public static function getMasterQuicklinkImageUploadFolder($offercategory = '') {
        $offer_category_folder = 'quickLinksIcons';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    public static function uploadMasterQuicklinkImageS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = $fileObj->getClientOriginalName();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getMasterQuicklinkImageUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;

            $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }

    public static function deleteS3File( $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            $exists = $s3->has($fileName);
            if($exists){
                $s3->delete($fileName); 
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error deleting the file.\n";
        }
        return true;
    }


    //banner social game images upload on s3 bucket
    public static function uploadSocialGamingBannerS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'banner-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getSocialGamingBannerUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;
            if($fileObj->getClientOriginalExtension() === 'svg'){
                $s3->getDriver()->put($s3filePath, file_get_contents($fileObj->getPathName()), [ 'visibility' => 'public', 'ContentType' => 'image/svg+xml']);
            }else{
                $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }

    public static function getSocialGamingBannerUploadFolder($offercategory = '') {
        $offer_category_folder = 'socialgaming';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    public static function uploadLivemusicS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'music-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getLivemusicBannerUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;
            if($fileObj->getClientOriginalExtension() === 'svg'){
                $s3->getDriver()->put($s3filePath, file_get_contents($fileObj->getPathName()), [ 'visibility' => 'public', 'ContentType' => 'image/svg+xml']);
            }else{
                $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }

    public static function getLivemusicBannerUploadFolder($offercategory = '') {
        $offer_category_folder = 'live_music';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    public static function uploadSpinMasterS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            if (empty($fileName)) {
                $fileName = 'spin-' . $offercategory->id . '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            }
            
            $currentDate = date("d-m-Y");
            $path = self::getSpinBannerUploadFolder($offercategory->id);
            $s3filePath = $path . $fileName;
            if($fileObj->getClientOriginalExtension() === 'svg'){
                $s3->getDriver()->put($s3filePath, file_get_contents($fileObj->getPathName()), [ 'visibility' => 'public', 'ContentType' => 'image/svg+xml']);
            }else{
                $s3->put($s3filePath, file_get_contents($fileObj->getPathName()), 'public');
            }
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }

    public static function getSpinBannerUploadFolder($offercategory = '') {
        $offer_category_folder = 'spin_the_wheel';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);

        return $path;
    }

    public static function addReportFileS3($fileObj, $offercategory = '', $fileName = '') {
        try {
            $s3 = Storage::disk('s3');
            // if (empty($fileName)) {
            //     $fileName = 'spin-' .  '-' . time() . '.' . $fileObj->getClientOriginalExtension();
            // }
            
            $currentDate = date("d-m-Y");
            $path = self::getReportFileS3();
            $s3filePath = $path.$offercategory;
           // print_r($s3filePath);die;
            $s3->put($s3filePath, file_get_contents($fileObj), 'public');
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
        return $s3filePath;
    }

    public static function getReportFileS3($offercategory = '') {
        $offer_category_folder = 'spwReports';
        $path = DIRECTORY_SEPARATOR . $offer_category_folder . DIRECTORY_SEPARATOR;
        self::checkDirectory(public_path() . $path);
        return $path;
    }

    
}
