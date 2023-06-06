<?php
/**
 * The class for handling validation requests from RedxHtmlController::deleteAction()
 * 
 * 
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class RedxHtmlDeleteRequest extends Request
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
            'ids' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => trans('admin::messages.required-select', ['name' => 'Redx Html']),
            'ids.numeric' => trans('admin::messages.error-numeric-id', ['name' => 'Redx Html']),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['ids'] = filter_var($input['ids'], FILTER_SANITIZE_NUMBER_INT);

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

        if ($is_delete == 1 || (!empty($own_delete))) {
            return true;
        } else {
            abort(403);
        }
    }
}
