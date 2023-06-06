<?php
/**
 * The class for managing faq categories specific actions.
 * 
 * 
 * @author Sachin Sonune <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Support\Str;
use Modules\Admin\Models\SilentOtas;
use Modules\Admin\Repositories\SilentOtasRepository;
use Modules\Admin\Http\Requests\SilentOtasCreateRequest;
use Modules\Admin\Http\Requests\SilentOtasUpdateRequest;
use Modules\Admin\Http\Requests\SilentOtasDeleteRequest;
use Modules\Admin\Repositories\AppVersionRepository;

class SilentOtasController extends Controller
{

    /**
     * The FaqCategoryRepository instance.
     *
     * @var Modules\Admin\Repositories\FaqCategoryRepository
     */
    protected $repository;
    protected $appVersionRepository;
    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\FaqCategoryRepository $repository
     * @return void
     */
    public function __construct(SilentOtasRepository $repository, AppVersionRepository $appVersionRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->appVersionRepository = $appVersionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return view as response
     */
    public function index(SilentOtas $silentOtas)
    {
        $silentOtaList = ['true' => 'True', 'false' => 'False'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        return view('admin::silent-otas.index', compact('silentOtas','appVersionList', 'silentOtaList'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $silentOtas = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $silentOtas = $silentOtas->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($silentOtas)
                ->addColumn('status', function ($silentOtas) {
                    $status = ($silentOtas->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('new_features', function ($silentOtas) {
                    $new_features = ($silentOtas->new_features == null) ? 'NA' : $silentOtas->new_features;
                    return $new_features;
                })
                ->addColumn('action', function ($silentOtas) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($silentOtas->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $silentOtas->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $silentOtas->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($silentOtas->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $silentOtas->id . ' created_by = ' . $silentOtas->created_by . ' ><i class="fa fa-trash-o"></i></a>';
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
        return view('admin::silent-otas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(SilentOtasCreateRequest $request)
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
    public function edit(SilentOtas $silentOtas)
    {
        $silentOtaList = ['true' => 'True', 'false' => 'False'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $response['success'] = true;
        $response['form'] = view('admin::silent-otas.edit', compact('silentOtas', 'appVersionList', 'silentOtaList'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(SilentOtasUpdateRequest $request, SilentOtas $silentOtas)
    {
        $response = $this->repository->update($request->all(), $silentOtas);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(SilentOtasDeleteRequest $request, SilentOtas $silentOtas)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Silent OTA'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Silent OTA'])];
        }

        return response()->json($response);
    }
}
