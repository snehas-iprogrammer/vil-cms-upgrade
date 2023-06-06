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

class DigitalOnboarding extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'digital_onboarding';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['prepaid_circles', 'postpaid_circles', 'status'];
    
    protected $casts = ['postpaid_circles' => 'array', 'prepaid_circles' => 'array'];
}
