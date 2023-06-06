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

class SpinMaster extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'spin_master';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['slot_name', 'title', 'sub_title','description', 'reward_type', 'reward_rank', 'device_os','internal_link','coupon_code','expiry_date','detail','status'];

    
}
