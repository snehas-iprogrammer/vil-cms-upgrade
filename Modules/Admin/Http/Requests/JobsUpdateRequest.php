<?php
/**
 * The class for handling validation requests from FaqController::update()
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class JobsUpdateRequest extends Request
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
            'app_version' => 'required',
            'title' => 'required',
            'rank' => 'required',
            'lob' => 'required',
            'device_os' => 'required',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'circle.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.circle')]),
            'app_version.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.app_version')]),
            'title.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_title')]),
            'name.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_name')]),
            'rank.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.banner_rank')]),
            'lob.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.lob')]),
            'device_os.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/banner.device_os')]),
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

        $is_edit = Auth::user()->can($action['as'], 'edit');
        $own_edit = Auth::user()->can($action['as'], 'own_edit');

        if ($is_edit == 1 || (!empty($own_edit) && ($this->faq->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
