<?php
/**
 * The class to present AppVersion model.
 * 
 * 
 * @author Sneha Shete<snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Models;

use Modules\Admin\Models\BaseModel;

class GuestBanners extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'guest_banners';
 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['banner_screen', 'app_version', 'banner_title','banner_image','device_os','rank','status'];

    
}
