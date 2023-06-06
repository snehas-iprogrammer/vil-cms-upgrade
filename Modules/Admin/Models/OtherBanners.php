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

class OtherBanners extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'other_banners';

    /**
     * Primary key used by the model.
     *
     * @var string
     */
    protected $fillable = ['banner_screen','circle','lob','brand','prepaid_persona','postpaid_persona','socid','socid_include_exclude','banner_title','internal_link','external_link','device_os','app_version','banner_name','status'];

    
}
