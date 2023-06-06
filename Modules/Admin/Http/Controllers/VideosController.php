<?php
/**
 * The class for managing Banner specific actions.
 *
 *
 * @author Tushar Dahiwale <tushard@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Admin\Models\Videos;
use Modules\Admin\Repositories\VideosRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\VideosCreateRequest;
use Modules\Admin\Http\Requests\VideosUpdateRequest;
use Modules\Admin\Http\Requests\VideosDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class VideosController extends Controller
{

    /**
     * The BannerRepository instance.
     *
     * @var Modules\Admin\Repositories\BannerRepository
     */
    protected $repository;
    /**
    * The CountryRepository instance.
    *
    * @var Modules\Admin\Repositories\AppVersionRepository
    */
   protected $appVersionRepository;

    /**
     * Create a new BannerController instance.
     *
     * @param  Modules\Admin\Repositories\BannerRepository $repository
     * @return void
     */
    public function __construct(VideosRepository $repository, AppVersionRepository $appVersionRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->appVersionRepository = $appVersionRepository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(Videos $videos)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $selectedSocidsArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedRedHierarchyArray = [];
        
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary', 'Both' => 'Both'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $planList = ['L' => 'L', 'UL' => 'UL', 'Both' => 'Both'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $socidList = ['All SOCID' => 'All SOCID', 'COCP' => 'All COCP RED Plans', 'IOIP' => 'All IOIP & INDIVIDUAL RED Plans', '25799385' => 'RED Add-On - 199', '25694515' => 'RED Basic 299', '25694735' => 'RED Entertainment', '25694645' => 'RED Entertainment+', '25904185' => 'RED MAX', '25826505' => 'REDX', '25898675' => 'RED X'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $redHierarchyList = ["All" => 'All',"primary" => 'Primary','secondary' => 'Secondary', 'individual' => 'Individual'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        return view('admin::videos.index', compact('planList','loginTypeList','linkTypeList','videos', 'selectedCirclesArray','osList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','redHierarchyList','selectedRedHierarchyArray'));
    }

    /**
     * Hadle Ajax Group Action
     *
     * @param  Illuminate\Http\Request $request
     * @return Response
     */
    public function groupAction(Request $request)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = trans('admin::messages.deleted', ['name' => 'Video']);
        } else {
            $response['status'] = 'fail';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Video']);
        }

        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return datatable
     */
    public function getData(Request $request)
    {
        $videos = $this->repository->data();
        //echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $videos = $videos->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($videos)
                ->addColumn('status', function ($videos) {
                    $status = ($videos->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('login_type', function ($videos) {
                    $loginType = 'NA';
                    if($videos->login_type != NULL){
                        $loginType = $videos->login_type;
                    }
                    
                    return $loginType;
                })
                ->addColumn('updated_at', function ($videos) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($videos->updated_at));
                    return $updated_at;
                })
                ->addColumn('app_version', function ($videos) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $versions = 'NA';
                    if($videos->app_version != NULL){
                        $versionsArr = explode(',',$videos->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('circle', function ($videos) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $circle = 'NA';
                    if($videos->circle != NULL){
                        $circleArr = explode(',',$videos->circle);
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
                ->addColumn('link', function ($videos) {
                    $actionList = '-';
                    if($videos->internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$videos->internal_link;
                    }
                    
                    if($videos->external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$videos->external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('video_title', function ($videos) {
                    $actionList = '-';
                    if($videos->video_link != NULL){
                        $actionList = '<b>'.$videos->video_title.'</b> </br> '.$videos->video_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($videos) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($videos->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $videos->id . '" data-action="edit" data-id="' . $videos->id . '" id="' . $videos->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($videos->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $videos->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })
//                ->filter(function ($instance) use ($request) {
//                    if ($request->has('banner_title')) {
//                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
//                            return Str::contains(strtolower($row['banner_title']), strtolower($request->get('banner_title'))) ? true : false;
//                        });
//                    }
//                    if ($request->has('status')) {
//                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
//                            return Str::equals($row['status'], $request->get('status')) ? true : false;
//                        });
//                    }
//                })
                ->make(true);
    }

    /**
     * Display a form to create new banner.
     *
     * @return view as response
     */
    public function create()
    {

        return view('admin::videos.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(VideosCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(Videos $videos)
    {
        $response['success'] = true;
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary', 'Both' => 'Both'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $planList = ['L' => 'L', 'UL' => 'UL', 'Both' => 'Both'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $socidList = ['All SOCID' => 'All SOCID', 'COCP' => 'All COCP RED Plans', 'IOIP' => 'All IOIP & INDIVIDUAL RED Plans', '25799385' => 'RED Add-On - 199', '25694515' => 'RED Basic 299', '25694735' => 'RED Entertainment', '25694645' => 'RED Entertainment+', '25904185' => 'RED MAX', '25826505' => 'REDX', '25898675' => 'RED X'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $redHierarchyList = ["All" => 'All',"primary" => 'Primary','secondary' => 'Secondary', 'individual' => 'Individual'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $selectedCirclesArray = [];
        if($videos->circle != NULL){
            $selectedCirclesArray = explode(',', $videos->circle);
        }
        $selectedAppVersionArray = [];
        if($videos->app_version != NULL){
            $selectedAppVersionArray = explode(',', $videos->app_version);
        }
        $selectedSocidsArray = [];
        if($videos->socid != NULL){
            $selectedSocidsArray = explode(',', $videos->socid);
        }
        $selectedpostpaidPersonaArray = [];
        if($videos->postpaid_persona != NULL){
            $selectedpostpaidPersonaArray = explode(',', $videos->postpaid_persona);
        }
        $selectedprepaidPersonaArray = [];
        if($videos->prepaid_persona != NULL){
            $selectedprepaidPersonaArray = explode(',', $videos->prepaid_persona);
        }
        $selectedRedHierarchyArray = [];
        if($videos->red_hierarchy != NULL){
            $selectedRedHierarchyArray = explode(',', $videos->red_hierarchy);
        }
        
        $response['form'] = view('admin::videos.edit', compact('planList','loginTypeList','videos', 'linkTypeList', 'selectedCirclesArray', 'osList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedpostpaidPersonaArray', 'selectedprepaidPersonaArray','redHierarchyList','selectedRedHierarchyArray'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(VideosUpdateRequest $request, Videos $videos)
    {
        $response = $this->repository->update($request->all(), $videos);

        return response()->json($response);
    }
    
}
