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

class Banner extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banners';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['banner_screen', 'circle', 'banner_size', 'language', 'device_width', 'banner_title', 'banner_name', 'banner_rank', 'device_os','internal_link','external_link','lob','login_type','brand','prepaid_persona','postpaid_persona', 'socid', 'socid_include_exclude', 'app_version','package_name','recommended_offer_plans'];

    
}
