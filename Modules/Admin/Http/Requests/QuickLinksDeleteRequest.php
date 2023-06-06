<?php
/**
 * The class for handling validation requests from BannerController::deleteAction()
 * 
 * 
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class QuickLinksDeleteRequest extends Request
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
            'id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('admin::messages.required-select', ['name' => trans('admin::controller/banner.id')]),
            'id.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/banner.id')]),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['id'] = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

        $this->replace($input);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $action = $this->route()->getAction();

        $is_delete = Auth::user()->can($action['as'], 'delete');
        $own_delete = Auth::user()->can($action['as'], 'own_delete');

        if ($is_delete == 1 || (!empty($own_delete) && ($this->faq_categories->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
