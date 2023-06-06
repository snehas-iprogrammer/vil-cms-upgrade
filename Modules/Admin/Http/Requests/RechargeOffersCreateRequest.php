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

class RechargeOffersCreateRequest extends Request
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
            'segment_name' => 'required|max:255|unique:recharge_offers', //alphaSpaces
            'route_name' => 'required|max:255|unique:recharge_offers',
            'referred_json' => 'required|json',
            'mrp_sequence_data' => 'required|json',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'segment_name.required' => trans('admin::messages.required-enter', ['name' => 'Segment Name']),
            'segment_name.max' => trans('admin::messages.error-maxlength-number', ['name' => 'Segment Name', 'number' => '255']),
            'segment_name.unique' => trans('admin::messages.error-taken', ['name' => 'Segment Name']),
            'route_name.required' => trans('admin::messages.required-enter', ['name' => 'Route Name']),
            'route_name.max' => trans('admin::messages.error-numeric', ['name' => 'Route Name', 'number' => '255']),
            'route_name.unique' => trans('admin::messages.error-taken', ['name' => 'Route Name']),
            'referred_json.required' => trans('admin::messages.required-enter', ['name' => 'Referred JSON']),
            'mrp_sequence_data.required' => trans('admin::messages.required-enter', ['name' => 'MRP Sequence Data']),
            'referred_json.json' => trans('admin::messages.invalid-json', ['name' => 'Referred_Json']),
            'mrp_sequence_data.json' => trans('admin::messages.invalid-json', ['name' => 'Mrp Sequence Data']),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/faq-category.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/faq-category.status')]),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['segment_name'] = filter_var($input['segment_name'], FILTER_SANITIZE_STRING);
        $input['route_name'] = filter_var($input['route_name'], FILTER_SANITIZE_STRING);
        //$input['referred_json'] = filter_var($input['referred_json'], FILTER_SANITIZE_STRING);
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
