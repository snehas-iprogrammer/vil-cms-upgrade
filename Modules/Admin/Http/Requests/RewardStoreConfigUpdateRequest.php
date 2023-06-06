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

class RewardStoreConfigUpdateRequest extends Request
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
            'lob' => 'required',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'lob.required' => trans('admin::messages.required-enter', ['name' => 'LOB']),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/faq-category.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/faq-category.status')]),
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
