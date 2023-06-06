<?php
/**
 * The helper library class for getting information of a logged in user from storage
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Services\Helper;

use Auth;
use Modules\Admin\Models\User;

class UserInfoHelper
{

    /**
     * fetch user details
     * @return String
     */
    public static function getAuthUserInfo()
    {
        return $userinfo = User::find(Auth::user()->id);
    }
}
