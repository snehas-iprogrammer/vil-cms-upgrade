<?php
/**
 * The class for handling validation requests from FaqCategoryController::Update()
 * 
 * 
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class AppVersionsUpdateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();
        $id = $this->app_versions->id;
        return [
            'app_version' => 'required|max:100|unique:app_versions,app_version,' . $id, //alphaSpaces
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'app_version.required' => trans('admin::messages.required-enter', ['name' => 'App version']),
            'app_version.max' => trans('admin::messages.error-maxlength-number', ['name' => 'App version', 'number' => '100']),
            'app_version.unique' => trans('admin::messages.error-taken', ['name' => 'App version']),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.status')]),
            'status.numeric' => trans('admin::messages.error-numeric', ['name' => trans('admin::controller/banner.status')]),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['app_version'] = filter_var($input['app_version'], FILTER_SANITIZE_STRING);
        $input['text_msg'] = filter_var($input['text_msg'], FILTER_SANITIZE_STRING);
        $input['status'] = filter_var($input['status'], FILTER_SANITIZE_NUMBER_INT);
        $this->merge($input);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        $action = $this->route()->getAction();

        $is_edit = Auth::user()->can($action['as'], 'edit');
        $own_edit = Auth::user()->can($action['as'], 'own_edit');

        if ($is_edit == 1 || (!empty($own_edit) && ($this->faq_categories->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
