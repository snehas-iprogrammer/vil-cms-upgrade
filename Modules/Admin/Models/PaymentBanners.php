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

class PaymentBanners extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_page_banners';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['lob','image','rank','status'];

    
}
