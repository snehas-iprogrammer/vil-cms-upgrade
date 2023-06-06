<?php
/**
 * The class to present Testimonials model.
 *
 *
 * @author SNeha S <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Models;

class Jobs extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs_education';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['name','url','circle','label', 'dashboard_logo', 'rank', 'device_os','internal_link','lob','prepaid_persona','postpaid_persona', 'socid', 'socid_include_exclude', 'app_version','youtube_link'];

    
}
