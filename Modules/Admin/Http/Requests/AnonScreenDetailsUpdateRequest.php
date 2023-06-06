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

class AnonScreenDetailsUpdateRequest extends Request
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
            'screen_id' => 'required',
            'screen_header' => 'required',
            'screen_title' => 'required',
            'screen_description' => 'required',
            'screen_packs_title' => 'required',
            'screen_packs_button_txt' => 'required',
            'screen_packs_button_link' => 'required',
            'status' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'screen_id.required' => trans('admin::messages.required-enter', ['name' => 'Screen ID']),
            'screen_header.required' => trans('admin::messages.required-enter', ['name' => 'Screen Header']),
            'screen_title.required' => trans('admin::messages.required-enter', ['name' => 'Screen Title']),
            'screen_description.required' => trans('admin::messages.required-enter', ['name' => 'Screen Description']),
            'screen_packs_title.required' => trans('admin::messages.required-enter', ['name' => 'Screen Packs Title']),
            'screen_packs_button_txt.required' => trans('admin::messages.required-enter', ['name' => 'Screen Packs Button Text']),
            'screen_packs_button_link.required' => trans('admin::messages.required-enter', ['name' => 'Screen Packs Button Link']),
            'status.required' => trans('admin::messages.required-enter', ['name' => trans('admin::controller/faq-category.status')]),
            'status.numeric' => trans('admin::messages.error-numeric-id', ['name' => trans('admin::controller/faq-category.status')]),
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['screen_id'] = filter_var($input['screen_id'], FILTER_SANITIZE_STRING);
        $input['screen_header'] = filter_var($input['screen_header'], FILTER_SANITIZE_STRING);
        $input['screen_title'] = filter_var($input['screen_title'], FILTER_SANITIZE_STRING);
        $input['screen_description'] = filter_var($input['screen_description'], FILTER_SANITIZE_STRING);
//        $input['faqs_json'] = filter_var($input['faqs_json'], FILTER_SANITIZE_STRING);
        $input['screen_packs_title'] = filter_var($input['screen_packs_title'], FILTER_SANITIZE_STRING);
        $input['screen_packs_button_txt'] = filter_var($input['screen_packs_button_txt'], FILTER_SANITIZE_STRING);
        $input['screen_packs_button_link'] = filter_var($input['screen_packs_button_link'], FILTER_SANITIZE_STRING);
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
