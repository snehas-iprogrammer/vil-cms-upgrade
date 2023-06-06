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

class Videos extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'videos';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['circle', 'lob', 'login_type', 'brand', 'postpaid_persona', 'prepaid_persona', 'socid', 'socid_include_exclude', 'plan', 'app_version', 'device_os', 'internal_link', 'external_link', 'video_title', 'video_link', 'status'];

    
}
