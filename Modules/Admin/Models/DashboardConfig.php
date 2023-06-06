<?php
/**
 * The class to present FaqCategory model.
 * 
 * 
 * @author Sachin Sonune <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Models;

class DashboardConfig extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['circle', 'lob', 'login_type', 'app_version', 'brand','rail_sequence', 'status','prepaid_persona','postpaid_persona','red_hierarchy'
    // 'header_menu',
     ];
    
    protected $casts = [//'header_menu' => 'array', 
                        'rail_sequence' => 'array'
                       ];
}
