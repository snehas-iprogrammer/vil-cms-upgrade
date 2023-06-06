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

class MasterQuickLinkCreateRequest extends Request
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
            'title' => 'required',
            'TealiumEvents' => 'required',
          //  'imageUrl' => 'required',
            'name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/masterquicklink.title')]),
            'title.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/masterquicklink.title')]),
            'TealiumEvents.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/masterquicklink.TealiumEvents')]),
            'name.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/masterquicklink.name')])
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
