<?php
/**
 * The class for handling validation requests from LocationsController::update()
 *
 *
 * @author Rahul Kadu <rahulk@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class LocationsUpdateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();
        //dd($this->all());
        return [
            'country_id' => 'required|numeric',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'location' => 'required|max:200|unique:locations,location,' . $this->locations->id . ',id,city_id,' . $this->all()['city_id'], //alphaSpaces
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/locations.country')]),
            'country_id.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/locations.country')]),
            'state_id.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/locations.state')]),
            'state_id.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/locations.state')]),
            'city_id.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/city.state')]),
            'city_id.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/locations.state')]),
            'location.required' => trans('admin::messages.error-required', ['name' => trans('admin::controller/locations.location')]),
            'location.alpha_spaces' => trans('admin::messages.error-alpha-spaces', ['name' => trans('admin::controller/locations.location')]),
            'location.max' => trans('admin::messages.error-maxlength-number', ['name' => trans('admin::controller/locations.location'), 'number' => '200']),
            'location.unique' => trans('admin::messages.error-taken-location', ['name' => trans('admin::controller/locations.location')]),
            'status.required' => trans('admin::messages.error-required-select', ['name' => trans('admin::controller/locations.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/locations.status')]),
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
        $input['city_id'] = filter_var($input['city_id'], FILTER_SANITIZE_NUMBER_INT);
        $input['location'] = filter_var($input['location'], FILTER_SANITIZE_STRING);
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

        if ($is_edit == 1 || (!empty($own_edit) && ($this->cities->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
