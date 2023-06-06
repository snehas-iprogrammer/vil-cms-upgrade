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

class GalleryCreateRequest extends Request
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
            'title' =>  'required|max:255',
            'order' => 'required|numeric',
            'image' => 'required|max:800',
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
