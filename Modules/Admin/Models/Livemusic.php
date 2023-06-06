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

class Livemusic extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'live_music';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['circle', 'price', 'subtitle','banner_title', 'banner_name', 'banner_rank', 'device_os','internal_link','lob','login_type','brand','prepaid_persona','postpaid_persona', 'socid', 'socid_include_exclude', 'app_version'];

    
}
