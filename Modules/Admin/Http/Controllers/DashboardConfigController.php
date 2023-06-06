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
use Modules\Admin\Models\DashboardConfig;
use Modules\Admin\Repositories\DashboardConfigRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\DashboardConfigCreateRequest;
use Modules\Admin\Http\Requests\DashboardConfigUpdateRequest;
use Modules\Admin\Http\Requests\DashboardConfigDeleteRequest;
use Illuminate\Http\Request;

class DashboardConfigController extends Controller
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
    public function __construct(DashboardConfigRepository $repository, AppVersionRepository $appVersionRepository)
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
    public function index(DashboardConfig $dashboardConfig)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedredhierarchyArray = [];
        
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary', 'Both' => 'Both'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];        
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        return view('admin::dashboard-config.index', compact('dashboardConfig', 'selectedAppVersionArray', 'selectedCirclesArray', 'circleList', 'lobList', 'loginTypeList', 'brandList', 'appVersionList','prepaidPersonaList','postpaidPersonaList','selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','redHierarchyList','selectedredhierarchyArray','socidIncludeExcludeList','osList'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $dashboardConfig = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $dashboardConfig = $dashboardConfig->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }
        // echo '<pre>'; print_r($dashboardConfig); die; 
        return Datatables::of($dashboardConfig)
                ->addColumn('status', function ($dashboardConfig) {
                    $status = ($dashboardConfig->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('login_type', function ($dashboardConfig) {
                    $loginType = 'NA';
                    if($dashboardConfig->login_type != NULL){
                        $loginType = $dashboardConfig->login_type;
                    }
                    
                    return $loginType;
                })
                ->addColumn('app_version', function ($dashboardConfig) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $versions = 'NA';
                    if($dashboardConfig->app_version != NULL){
                        $versionsArr = explode(',',$dashboardConfig->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('circle', function ($dashboardConfig) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $circle = 'NA';
                    if($dashboardConfig->circle != NULL){
                        $circleArr = explode(',',$dashboardConfig->circle);
                        $circleTextArr = [];
                        foreach ($circleArr as $key => $value) {
                            if (array_key_exists($value, $circleList)) {
                                $circleTextArr[$key] = $circleList[$value];
                            }
                        }
                        $circle = implode(", ",$circleTextArr);
                    }
                    
                    return $circle;
                })
                ->addColumn('action', function ($dashboardConfig) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($dashboardConfig->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $dashboardConfig->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $dashboardConfig->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($dashboardConfig->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $dashboardConfig->id . ' created_by = ' . $dashboardConfig->created_by . ' ><i class="fa fa-trash-o"></i></a>';
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
        return view('admin::dashboard-config.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(DashboardConfigCreateRequest $request)
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
    public function edit(DashboardConfig $dashboardConfig)
    {
        $response['success'] = true;
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary', 'Both' => 'Both'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $selectedCirclesArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedredhierarchyArray = [];
        if($dashboardConfig->prepaid_persona != NULL){
            $selectedprepaidPersonaArray = explode(',', $dashboardConfig->prepaid_persona);
        }
        if($dashboardConfig->postpaid_persona != NULL){
            $selectedpostpaidPersonaArray = explode(',', $dashboardConfig->postpaid_persona);
        }
        if($dashboardConfig->circle != NULL){
            $selectedCirclesArray = explode(',', $dashboardConfig->circle);
        }
        if($dashboardConfig->red_hierarchy != NULL){
            $selectedredhierarchyArray = explode(',', $dashboardConfig->red_hierarchy);
        }
        
        $selectedAppVersionArray = [];
        if($dashboardConfig->app_version != NULL){
            $selectedAppVersionArray = explode(',', $dashboardConfig->app_version);
        }
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $response['form'] = view('admin::dashboard-config.edit', compact('dashboardConfig', 'selectedAppVersionArray', 'selectedCirclesArray', 'circleList', 'lobList', 'loginTypeList', 'brandList', 'appVersionList','prepaidPersonaList','postpaidPersonaList','selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','redHierarchyList','selectedredhierarchyArray','socidIncludeExcludeList','osList'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(DashboardConfigUpdateRequest $request, DashboardConfig $dashboardConfig)
    {
        $response = $this->repository->update($request->all(), $dashboardConfig);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(DashboardConfigDeleteRequest $request, DashboardConfig $dashboardConfig)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Dashboard Config'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Dashboard Config'])];
        }

        return response()->json($response);
    }
}
