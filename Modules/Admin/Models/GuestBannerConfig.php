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

class GuestBannerConfig extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'guest_config';
 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['device_os', 'app_version', 'category','isRail','railHeader','rank','logo','isBanner','view_all_redirection','status'];

    
}
