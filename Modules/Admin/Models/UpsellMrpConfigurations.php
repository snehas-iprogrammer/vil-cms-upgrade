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

class UpsellMrpConfigurations extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'upsell_mrp_configurations';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['current_mrp','upsell_mrp','category','image','bottom_padding'];

    
}
