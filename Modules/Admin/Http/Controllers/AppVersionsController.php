<?php
/**
 * The class for managing faq categories specific actions.
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
use Modules\Admin\Models\AppVersion;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\AppVersionsCreateRequest;
use Modules\Admin\Http\Requests\AppVersionsUpdateRequest;
use Modules\Admin\Http\Requests\AppVersionsDeleteRequest;

class AppVersionsController extends Controller
{

    /**
     * The FaqCategoryRepository instance.
     *
     * @var Modules\Admin\Repositories\FaqCategoryRepository
     */
    protected $repository;

    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\FaqCategoryRepository $repository
     * @return void
     */
    public function __construct(AppVersionRepository $repository)
    {
        
        parent::__construct();
        //$this->middleware('acl');
        $this->repository = $repository;
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return view as response
     */
    public function index()
    {
        $isHungamaEnabledList = ['1' => 'True','0' => 'False'];
        $isViMTVSDKEnabledList = ['1' => 'True','0' => 'False'];
        return view('admin::app-versions.index', compact('isHungamaEnabledList','isViMTVSDKEnabledList'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $appVersions = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $appVersions = $appVersions->filter(function ($row) {
                return true;
            });
        }

        return Datatables::of($appVersions)
                ->addColumn('status', function ($appVersions) {
                    $status = ($appVersions->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })->escapeColumns([])
                ->addColumn('is_hungama_enabled', function ($appVersions) {
                    $is_hungama_enabled = ($appVersions->is_hungama_enabled) ? 'True':'False' ;
                    return $is_hungama_enabled;
                })
                ->addColumn('is_vimtv_sdk_enabled', function ($appVersions) {
                    $is_vimtv_sdk_enabled = ($appVersions->is_vimtv_sdk_enabled) ? 'True':'False' ;
                    return $is_vimtv_sdk_enabled;
                })
                ->addColumn('silentOTA', function ($appVersions) {
                    $silentOTA = ($appVersions->silentOTA) ? 'True':'False' ;
                    return $silentOTA;
                })
                ->addColumn('action', function ($appVersions) {
                    $actionList = '';
                    $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $appVersions->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $appVersions->id . '"><i class="fa fa-pencil"></i></a>';
                    $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $appVersions->id . ' ><i class="fa fa-trash-o"></i></a>';
                    return $actionList;
                })
                ->escapeColumns([])
                ->make(true);
    }

    /**
     * Display a form to create new faq category.
     *
     * @return view as response
     */
    public function create()
    {
        return view('admin::app-versions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(AppVersionsCreateRequest $request)
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
    public function edit(AppVersion $appVersion)
    {
        $response['success'] = true;
        $isHungamaEnabledList = ['1' => 'True','0' => 'False'];
        $isViMTVSDKEnabledList = ['1' => 'True','0' => 'False'];
        $response['form'] = view('admin::app-versions.edit', compact('appVersion', 'isHungamaEnabledList', 'isViMTVSDKEnabledList'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(AppVersionsUpdateRequest $request, AppVersion $appVersion)
    {
        $response = $this->repository->update($request->all(), $appVersion);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(AppVersionsDeleteRequest $request, AppVersion $appVersion)
    {
        //dd("here" . $faqCategory);
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'App version'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'App version'])];
        }

        return response()->json($response);
    }
}
