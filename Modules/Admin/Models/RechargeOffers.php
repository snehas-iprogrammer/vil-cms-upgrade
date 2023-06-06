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

class RechargeOffers extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recharge_offers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['segment_name', 'route_name', 'referred_json', 'mrp_sequence_data', 'status'];
    
    protected $casts = ['referred_json' => 'array', 'mrp_sequence_data' => 'array'];
}
