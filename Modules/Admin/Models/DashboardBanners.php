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

class DashboardBanners extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_banners';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['page_name','rank','image','redirection_link'];

    
}
