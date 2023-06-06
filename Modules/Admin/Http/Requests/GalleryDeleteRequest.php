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

class GalleryDeleteRequest extends Request
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
            'ids.required' => trans('admin::messages.required-select', ['name' => trans('admin::controller/testimonials.testimonials')]),
            'ids.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/testimonials.testimonials')]),
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

        if ($is_delete == 1 || (!empty($own_delete) && ($this->gallery->created_by == Auth::user()->id))) {
            return true;
        } else {
            abort(403);
        }
    }
}
