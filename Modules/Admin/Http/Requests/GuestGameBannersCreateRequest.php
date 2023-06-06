<?php
/**
 * The class for handling validation requests from FaqController::store()
 *
 *
 * @author Sneha Shete<snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class GuestGameBannersCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();
        // dd($this);
        return [
            'banner_screen' => 'required',
            'app_version' => 'required',
            'banner_title' => 'required',
            'rank' => 'required',
            'device_os' => 'required',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'banner_screen.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_screen')]),
            'app_version.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.app_version')]),
            'banner_title.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_title')]),
            'banner_name.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_name')]),
            'rank.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_rank')]),
            'device_os.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.device_os')]),
            'status.required' => trans('admin::messages.required-enter', ['name' => "Status"]),
            'status.numeric' => trans('admin::messages.error-numeric', ['name' => "Status"]),
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
