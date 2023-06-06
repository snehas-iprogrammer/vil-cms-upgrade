<?php
/**
 * The class for handling validation requests from FaqController::store()
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class UpsellMrpConfigurationsCreateRequest extends Request
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
            'current_mrp' => 'required|unique:upsell_mrp_configurations',
            'upsell_mrp' => 'required',
            'category' => 'required',
            'bottom_padding' => 'required',
            'bottom_padding' => 'required',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'current_mrp.required' => trans('admin::messages.required-enter', ['name' => 'Current MRP']),
            'current_mrp.unique' => trans('admin::messages.error-taken', ['name' => 'Current MRP']),
            'upsell_mrp.required' => trans('admin::messages.required-enter', ['name' => 'Upsell MRP']),
            'category.required' => trans('admin::messages.required-enter', ['name' => 'Category']),
            'bottom_padding.required' => trans('admin::messages.required-enter', ['name' => 'Bottom Padding']),
            'is_large.required' => trans('admin::messages.required-enter', ['name' => 'Is Large Image']),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.status')]),
            'status.numeric' => trans('admin::messages.error-numeric', ['name' => trans('admin::controller/banner.status')]),
        ];
    }

    /**
     * Sanitize all input fieds and replace
     */
    public function sanitize()
    {
        $input = $this->all();
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
        $action = $this->route()->getAction();

        $status = Auth::user()->can($action['as'], 'store');
        if (empty($status)) {
            abort(403);
        }
        return true;
    }
}
