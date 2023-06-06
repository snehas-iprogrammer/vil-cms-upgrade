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

class AnonScreenDetails extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'anon_screen_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['screen_id', 'screen_header', 'screen_title', 'screen_description', 'screen_packs_title', 'screen_packs_button_txt', 'screen_packs_button_link', 'status', 'faqs_json', 'mrps_json'];
    
    protected $casts = ['faqs_json' => 'array', 'mrps_json' => 'array'];
}
