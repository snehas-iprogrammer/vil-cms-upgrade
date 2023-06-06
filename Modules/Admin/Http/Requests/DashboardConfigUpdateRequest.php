<?php
/**
 * The class for handling validation requests from FaqCategoryController::Update()
 * 
 * 
 * @author Sachin Sonune <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class DashboardConfigUpdateRequest extends Request
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
            'circle' => 'required',
            'lob' => 'required',
            'brand' => 'required',
            'app_version' => 'required',
            'status' => 'required|numeric',
            'new_dashboard_rail_sequence' => 'required|json',
            'rail_titles' => 'required|json',
            'rail_sequence' => 'required|json'
        ];
    }

    public function messages()
    {
        return [
            'circle.required' => trans('admin::messages.required-enter', ['name' => 'Circle']),
            'lob.required' => trans('admin::messages.required-enter', ['name' => 'LOB']),
            'brand.required' => trans('admin::messages.required-enter', ['name' => 'Brand']),
            'app_version.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.app_version')]),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/faq-category.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/faq-category.status')]),
            'new_dashboard_rail_sequence.required' => trans('admin::messages.required-enter', ['name' => 'New rail sequence']),
            'new_dashboard_rail_sequence.json' => trans('admin::messages.invalid-json', ['name' => 'New rail sequence']),
            'rail_titles.required' => trans('admin::messages.required-enter', ['name' => 'Rail title']),
            'rail_titles.json' => trans('admin::messages.invalid-json', ['name' => 'Rail title']),
            'rail_sequence.required' => trans('admin::messages.required-enter', ['name' => 'Rail sequence']),
            'rail_sequence.json' => trans('admin::messages.invalid-json', ['name' => 'Rail sequence'])
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['status'] = filter_var($input['status'], FILTER_SANITIZE_NUMBER_INT);
        if (Auth::check()) {
            $input['updated_by'] = filter_var(Auth::user()->id, FILTER_SANITIZE_NUMBER_INT);
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

        $is_edit = Auth::user()->can($action['as'], 'edit');
        $own_edit = Auth::user()->can($action['as'], 'own_edit');

        if ($is_edit == 1 || (!empty($own_edit) && ($this->faq_categories->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
