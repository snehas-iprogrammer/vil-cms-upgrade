<?php
/**
 * The class for handling validation requests from CityController::store()
 * 
 * 
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class CityCreateRequest extends Request
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
            'country_id' => 'required|numeric',
            'state_id' => 'required|numeric',
            'name' => 'required|max:200|unique:cities,name,null,id,state_id,' . $this->all()['state_id'], //alphaSpaces
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/city.country')]),
            'country_id.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/city.country')]),
            'state_id.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/city.state')]),
            'state_id.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/city.state')]),
            'name.required' => trans('admin::messages.error-required', ['name' => trans('admin::controller/city.name')]),
            'name.alpha_spaces' => trans('admin::messages.error-alpha-spaces', ['name' => trans('admin::controller/city.name')]),
            'name.max' => trans('admin::messages.error-maxlength-number', ['name' => trans('admin::controller/city.name'), 'number' => '200']),
            'name.unique' => trans('admin::messages.error-taken-city', ['name' => trans('admin::controller/city.name')]),
            'status.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/city.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/city.status')]),
        ];
    }

    /**
     * Sanitize all input fieds and replace
     */
    public function sanitize()
    {
        $input = $this->all();

        $input['country_id'] = filter_var($input['country_id'], FILTER_SANITIZE_NUMBER_INT);
        $input['state_id'] = filter_var($input['state_id'], FILTER_SANITIZE_NUMBER_INT);
        $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
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
