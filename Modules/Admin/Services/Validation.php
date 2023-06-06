<?php

namespace Modules\Admin\Services;

use Illuminate\Validation\Validator;
use Hash;
use Illuminate\Support\Facades\Route;

class Validation extends Validator
{

    public function validateTags($attribute, $value, $parameters)
    {
        return preg_match("/^[A-Za-z0-9-éèàù]{1,50}?(,[A-Za-z0-9-éèàù]{1,50})*$/", $value);
    }

    public function validateAlphaSpaces($attribute, $value, $parameters)
    {
        return preg_match('/^[\pL\s]+$/u', $value);
    }

    public function validateEmailMulti($attribute, $value)
    {
        $emails = explode(',', $value);
        foreach ($emails as $email) {
            $status = $this->validateEmail($attribute, $email);
            if ($status != '1') {
                return false;
            }
        }
        return true;
    }

    public function validateAddrSpecEmail($attribute, $value, $parameters)
    {
        if (preg_match('/\</', $value)) {
            $str = explode('<', $value);
            $email = preg_replace('/\>/', '', $str[1]);
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        } else {
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        }
    }

    public function validateCurrentPassword($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'current_password');
        if (Hash::check($value, $parameters[2])) {
            return true;
        } else {
            return false;
        }
    }

    public function validateCurrentNewPassword($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'password');
        if (Hash::check($value, $parameters[2])) {
            return false;
        } else {
            return true;
        }
    }

    public function validateLinkRoute($attribute, $value, $parameters)
    {
        if (Route::has($value)) {
            return true;
        } else {
            return false;
        }
    }
}
