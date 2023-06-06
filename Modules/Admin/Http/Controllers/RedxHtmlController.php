<?php
/**
 * The class for managing Redx Html specific actions.
 * 
 * 
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Support\Str;
use Modules\Admin\Models\RedxHtml;
use Modules\Admin\Repositories\RedxHtmlRepository;
use Modules\Admin\Http\Requests\RedxHtmlCreateRequest;
use Modules\Admin\Http\Requests\RedxHtmlUpdateRequest;
use Modules\Admin\Http\Requests\RedxHtmlDeleteRequest;

class RedxHtmlController extends Controller
{

    /**
     * The RedxHtmlRepository instance.
     *
     * @var Modules\Admin\Repositories\RedxHtmlRepository
     */
    protected $repository;

    /**
     * Create a new RedxHtmlController instance.
     *
     * @param  Modules\Admin\Repositories\RedxHtmlRepository $repository
     * @return void
     */
    public function __construct(RedxHtmlRepository $repository)
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
    public function index()
    {
        return view('admin::redx-html.index');
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $redxHtml = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $redxHtml = $redxHtml->filter(function ($row) {
                return true;
            });
        }

        return Datatables::of($redxHtml)
                ->addColumn('action', function ($redxHtml) {
                    $actionList = '';
                    $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $redxHtml->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $redxHtml->id . '"><i class="fa fa-pencil"></i></a>';
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
        return view('admin::redx-html.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\RedxHtmlCreateRequest $request
     * @return json encoded Response
     */
    public function store(RedxHtmlCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified faq category.
     *
     * @param  Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function edit(RedxHtml $redxHtml)
    {
        $response['success'] = true;
        $response['form'] = view('admin::redx-html.edit', compact('redxHtml'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\RedxHtmlCreateRequest $request
     * @return json encoded Response
     */
    public function update(RedxHtmlUpdateRequest $request, RedxHtml $redxHtml)
    {
        $response = $this->repository->update($request->all(), $redxHtml);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\RedxHtmlDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(RedxHtmlDeleteRequest $request, RedxHtml $redxHtml)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Redx Html'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Redx Html'])];
        }

        return response()->json($response);
    }
}
