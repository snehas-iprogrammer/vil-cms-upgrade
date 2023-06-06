<?php
/**
 * The class for handling validation requests from ConfigCategoryController::store()
 * 
 * 
 * @author Sneha Shete <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class TabCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();
        return [
            'name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('admin::messages.required-enter', ['name' => 'name']),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        //$input['new_features'] = filter_var($input['new_features'], FILTER_SANITIZE_STRING);
        $input['status'] = filter_var($input['status'], FILTER_SANITIZE_NUMBER_INT);
        if (Auth::check()) {
            $input['created_by'] = filter_var(Auth::user()->id, FILTER_SANITIZE_NUMBER_INT);
        }

        $this->merge($input);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $action = $this->route()->getAction();

        $status = Auth::user()->can($action['as'], 'store');
        if (empty($status)) {
            abort(403);
        }
        return true;
    }
}
