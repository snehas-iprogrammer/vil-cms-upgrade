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

class MasterQuickLink extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quicklink';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['name','title', 'imageUrl', 'internalLink', 'externalLink', 'TealiumEvents', 'sequenceNumber', 'cardType', 'tag', 'status','is_animated'];

    
}
