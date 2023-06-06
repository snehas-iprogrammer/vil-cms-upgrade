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

class QuickLinks extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quicklink_segments';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['app_version', 'lob', 'persona', 'plan', 'socid', 'socid_include_exclude', 'red_hierarchy', 'referred_json', 'status'];
    
    protected $casts = ['referred_json' => 'array' ];
}
