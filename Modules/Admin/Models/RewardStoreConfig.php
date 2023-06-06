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

class RewardStoreConfig extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reward_store_config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'lob', 'header_text', 'cta', 'cta_description', 'cta_internal_link', 'cta_external_link', 'claim_rewards_cta', 'claim_rewards_text', 'claim_internal_link', 'claim_external_link', 'banners_json', 'partner_banners_json', 'status'];
    
    protected $casts = ['banners_json' => 'array', 'partner_banners_json' => 'array'];
}
