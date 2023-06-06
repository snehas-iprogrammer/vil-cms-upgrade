<?php
/**
 * The class for handling validation requests from ConfigCategoryController::store()
 * 
 * 
 * @author Prashant Birajdar <prashantb@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class MenuGroupCreateRequest extends Request
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
            'name' => 'required|max:150|unique:menu_groups', //alphaSpaces
            'position' => 'required|numeric|unique:menu_groups'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/menu-group.name')]),
            'name.max' => trans('admin::messages.error-maxlength-number', ['name' => trans('admin::controller/menu-group.name'), 'number' => '150']),
            'name.alpha_spaces' => trans('admin::messages.error-alpha-spaces', ['name' => trans('admin::controller/menu-group.name')]),
            'name.unique' => trans('admin::messages.error-taken', ['name' => trans('admin::controller/menu-group.name')]),
            'position.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/menu-group.position')]),
            'position.numeric' => trans('admin::messages.error-numeric', ['name' => trans('admin::controller/menu-group.position')]),
            'position.unique' => trans('admin::messages.error-taken', ['name' => trans('admin::controller/menu-group.position')]),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $input['position'] = filter_var($input['position'], FILTER_SANITIZE_NUMBER_INT);

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
