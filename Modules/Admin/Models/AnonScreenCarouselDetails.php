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

class AnonScreenCarouselDetails extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'anon_screen_carousel_details';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['anon_screen_id','title','description','media_type','media','shape', 'rank', 'status'];

    /**
     * get model when used in join
     * 
     * @return type
     */
    public function screens()
    {
        return $this->belongsTo('Modules\Admin\Models\AnonScreenDetails', 'anon_screen_id', 'id');
    }
}
