<?php
/**
 * The class for handling validation requests from TestimonialsController::deleteAction()
 *
 *
 * @author Sachin S. <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Requests;

use Modules\Admin\Http\Requests\Request;
use Auth;

class GalleryUpdateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();
        $id = $this->gallery->id;
        return [
            'title' =>  'required|max:255',
            'order' => 'required|numeric',
            'status' => 'required|numeric',
            'image_alt_text' => 'max:100'
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        //$input['picture'] = filter_var($input['picture'], FILTER_SANITIZE_STRING);
        if (Auth::user()->check()) {
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

        if ($is_edit == 1 || (!empty($own_edit) && ($this->gallery->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
