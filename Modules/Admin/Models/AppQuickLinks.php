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

class AppQuickLinks extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quicklinks_segment';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['app_version', 'lob', 'prepaid_persona','postpaid_persona', 'plan', 'socid', 'socid_include_exclude', 'red_hierarchy', 'referred_json', 'status','quicklink_id','rank','circle'];
    
    protected $casts = ['referred_json' => 'array' ];
}
