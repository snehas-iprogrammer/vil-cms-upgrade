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

class QuickRechargeDetails extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quick_recharge_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['circle', 'mrp', 'route_name', 'referred_json', 'status'];
    
    protected $casts = ['referred_json' => 'array' ];
}
