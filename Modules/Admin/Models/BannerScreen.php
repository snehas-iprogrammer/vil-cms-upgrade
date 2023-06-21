<?php
/**
 * The class to present Testimonials model.
 *
 *
 * @author Sachin S <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Models;

class BannerScreen extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banner_screens';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['screen_name','is_timestamp_check', 'type', 'screen_title', 'is_component','status'];

    
}
