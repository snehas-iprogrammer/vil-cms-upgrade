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
use Modules\Admin\Models\RewardStoreConfig;
use Modules\Admin\Repositories\RewardStoreConfigRepository;
use Modules\Admin\Http\Requests\RewardStoreConfigCreateRequest;
use Modules\Admin\Http\Requests\RewardStoreConfigUpdateRequest;
use Modules\Admin\Http\Requests\RewardStoreConfigDeleteRequest;
use Illuminate\Http\Request;

class RewardStoreConfigController extends Controller
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
    public function __construct(RewardStoreConfigRepository $repository)
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
    public function index(RewardStoreConfig $rewardStoreConfig)
    {
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        return view('admin::reward-store-config.index', compact('rewardStoreConfig','lobList'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $rewardStoreConfig = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $rewardStoreConfig = $rewardStoreConfig->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }
//        echo '<pre>'; print_r($rewardStoreConfig); die; 
        return Datatables::of($rewardStoreConfig)
                ->addColumn('status', function ($rewardStoreConfig) {
                    $status = ($rewardStoreConfig->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('cta_link', function ($rewardStoreConfig) {
                    $actionList = '-';
                    if($rewardStoreConfig->cta_internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$rewardStoreConfig->cta_internal_link;
                    }
                    
                    if($rewardStoreConfig->cta_external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$rewardStoreConfig->cta_external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($rewardStoreConfig) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($rewardStoreConfig->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $rewardStoreConfig->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $rewardStoreConfig->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($rewardStoreConfig->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $rewardStoreConfig->id . ' created_by = ' . $rewardStoreConfig->created_by . ' ><i class="fa fa-trash-o"></i></a>';
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
        return view('admin::reward-store-config.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(RewardStoreConfigCreateRequest $request)
    {
        // echo '<pre>'; print_r($request->all()); die; 
        $response = $this->repository->create($request->all());
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified faq category.
     *
     * @param  Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function edit(RewardStoreConfig $rewardStoreConfig)
    {
        $response['success'] = true;
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $response['form'] = view('admin::reward-store-config.edit', compact('rewardStoreConfig','lobList'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(RewardStoreConfigUpdateRequest $request, RewardStoreConfig $rewardStoreConfig)
    {
//         echo '<pre>'; print_r($request->all()); die; 
        $response = $this->repository->update($request->all(), $rewardStoreConfig);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(RewardStoreConfigDeleteRequest $request, RewardStoreConfig $rewardStoreConfig)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Reward Store Config'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Reward Store Config'])];
        }

        return response()->json($response);
    }
}
