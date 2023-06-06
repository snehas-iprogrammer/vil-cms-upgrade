<?php
/**
 * To present User Model with associated authentication
 *
 *
 * @author Gaurav Patel <gauravp@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Models;

use Illuminate\Auth\Authenticatable;
use Modules\Admin\Models\BaseModel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Services\Access\Traits\UserHasLink;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable,
        CanResetPassword,
        SoftDeletes,
        UserHasLink;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'first_name', 'last_name', 'gender', 'contact', 'status', 'skip_ip_check', 'created_by'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Serves as a "black-list" instead of a "white-list":
     * 
     *  @var array
     */
    protected $guarded = ['id'];

    /**
     * Enables soft delete to
     * 
     *  @var array
     */
    //protected $softDelete = true;
    protected $dates = ['deleted_at'];

    /**
     * used to join with Modules\Admin\Models\UserType
     * 
     * @return type
     */
    public function userType()
    {
        return $this->belongsTo('Modules\Admin\Models\UserType');
    }
}
