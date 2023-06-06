<?php
/**
 * The class for managing tab actions.
 * 
 * 
 * @author Sneha Shete <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Support\Str;
use Modules\Admin\Models\Tab;
use Modules\Admin\Repositories\TabRepository;
use Modules\Admin\Http\Requests\TabCreateRequest;
use Modules\Admin\Http\Requests\TabUpdateRequest;
use Modules\Admin\Http\Requests\TabDeleteRequest;

class TabController extends Controller
{

    /**
     * The TabRepository instance.
     *
     * @var Modules\Admin\Repositories\TabRepository
     */
    protected $repository;
    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\TabRepository $repository
     * @return void
     */
    public function __construct(TabRepository $repository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return view as response
     */
    public function index(Tab $Tab)
    {
        return view('admin::tab.index');
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $Tab = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $Tab = $Tab->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($Tab)
                ->addColumn('status', function ($Tab) {
                    $status = ($Tab->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })

                ->addColumn('action', function ($Tab) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($Tab->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $Tab->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $Tab->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($Tab->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $Tab->id . ' created_by = ' . $Tab->created_by . ' ><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })
                ->make(true);
    }

    /**
     * Display a form to create new faq category.
     *
     * @return view as response
     */
    public function create()
    {
        return view('admin::tab.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\TabCreateRequest $request
     * @return json encoded Response
     */
    public function store(TabCreateRequest $request)
    {

        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified faq category.
     *
     * @param  Modules\Admin\Models\tab $tab
     * @return json encoded Response
     */
    public function edit(Tab $Tab)
    {
        $response['success'] = true;
        $response['form'] = view('admin::tab.edit', compact('Tab'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\TabUpdateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(TabUpdateRequest $request, Tab $Tab)
    {
        $response = $this->repository->update($request->all(), $Tab);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\TabDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(TabDeleteRequest $request, Tab $Tab)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Tab'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Tab'])];
        }

        return response()->json($response);
    }
}
