<?php
/**
 * The class for handling validation requests from ConfigCategoryController::store()
 * 
 * 
 * @author Sachin Sonune <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class SilentOtasCreateRequest extends Request
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
            'app_version' => 'required|unique:silent_otas',
            'silent_ota' => 'required|max:10',
            //'new_features' => 'required',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'app_version.required' => trans('admin::messages.required-enter', ['name' => 'App Version']),
            'app_version.unique' => trans('admin::messages.error-taken', ['name' => 'App Version']),
            'silent_ota.required' => trans('admin::messages.required-enter', ['name' => 'Silent OTA']),
            'silent_ota.max' => trans('admin::messages.error-numeric', ['name' => 'Silent OTA', 'number' => '10']),
            //'new_features.required' => trans('admin::messages.required-enter', ['name' => 'New Features']),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/faq-category.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/faq-category.status')]),
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
