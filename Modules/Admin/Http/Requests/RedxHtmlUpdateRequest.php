<?php
/**
 * The class for handling validation requests from RedxHtmlController::Update()
 * 
 * 
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;
use HTML;

class RedxHtmlUpdateRequest extends Request
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
            'redx_html' => 'required', //alphaSpaces
        ];
    }

    public function messages()
    {
        return [
            'redx_html.required' => trans('admin::messages.required-enter', ['name' => 'Redx Html'])
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        //$input['redx_html'] = HTML::entities($input['redx_html']);
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

        if ($is_edit == 1 || (!empty($own_edit))) {
            return true;
        } else {
            abort(403);
        }
    }
}
