<?php
/**
 * The class to present Testimonials model.
 *
 *
 * @author Rahul K <rahulk@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Models;

class Gallery extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gallery';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image', 'title', 'status', 'order', 'image_alt_text'];

}
