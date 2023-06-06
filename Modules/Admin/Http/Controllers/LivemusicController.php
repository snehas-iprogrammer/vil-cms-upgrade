<?php
/**
 * The class for managing Livemusic specific actions.
 *
 *
 * @author Sneha shete <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Admin\Models\Livemusic;
use Modules\Admin\Repositories\LivemusicRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Repositories\TabRepository;
use Modules\Admin\Http\Requests\LivemusicCreateRequest;
use Modules\Admin\Http\Requests\LivemusicUpdateRequest;
use Modules\Admin\Http\Requests\LivemusicDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class LivemusicController extends Controller
{

    /**
     * The LivemusicRepository instance.
     *
     * @var Modules\Admin\Repositories\LivemusicRepository
     */
    protected $repository;
    /**
    * The CountryRepository instance.
    *
    * @var Modules\Admin\Repositories\AppVersionRepository
    */
   protected $appVersionRepository;

    /**
    * The tabRepository instance.
    *
    * @var Modules\Admin\Repositories\TabRepository
    */
    protected $tabRepository;

    /**
     * Create a new LivemusicController instance.
     *
     * @param  Modules\Admin\Repositories\LivemusicRepository $repository
     * @return void
     */
    public function __construct(LivemusicRepository $repository, AppVersionRepository $appVersionRepository, TabRepository $tabRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->appVersionRepository = $appVersionRepository;
        $this->tabRepository    =   $tabRepository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(Livemusic $livemusic)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $selectedSocidsArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedServiceTypeArray = [];
        $selectedRedhierarchyArray = [];
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
       // $tabsList = ["Recommended" => 'Recommended','Unlimited' => 'Unlimited', 'All-rounderpacks' => 'All-rounderpacks', 'Internet' => 'Internet', 'Talktime' => 'Talktime', 'SMS' => 'SMS', 'Roaming' => 'Roaming', 'PlanVoucher' => 'PlanVoucher', 'Voice' => 'Voice', 'Game' => 'Game', 'Campaign' => 'Campaign', 'Downtime' => 'Downtime', 'vi_mtv_1' => 'vi_mtv_1', 'vi_mtv_2' => 'vi_mtv_2', 'vi_mtv_3' => 'vi_mtv_3', 'Specific-Screen' => 'Specific-Screen'];
        $tabsList = $this->tabRepository->listTabData()->toArray();
        $screenList = ['MusicDasboard' => 'Music Dashboard','LiveMusicEvent'=>'Live Music Event'];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $planList = ['L'=>'Limited','UL'=>'Unlimited'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary', 'Both' => 'Both'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $socidList = ['All SOCID' => 'All SOCID', 'COCP' => 'All COCP RED Plans', 'IOIP' => 'All IOIP & INDIVIDUAL RED Plans', '25799385' => 'RED Add-On - 199', '25694515' => 'RED Basic 299', '25694735' => 'RED Entertainment', '25694645' => 'RED Entertainment+', '25904185' => 'RED MAX', '25826505' => 'REDX', '25898675' => 'RED X'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        return view('admin::livemusic.index', compact('loginTypeList','selectedRedhierarchyArray','serviceTypeList','selectedServiceTypeArray','redHierarchyList','linkTypeList','livemusic', 'tabsList', 'selectedCirclesArray','osList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','planList'));
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
        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return datatable
     */
    public function getData(Request $request)
    {
        $livemusic = $this->repository->data();
      //  echo '<pre>'; print_r($request); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $livemusic = $livemusic->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($livemusic)

                ->addColumn('checkbox', function ($livemusic) {
                    $status = '<input class="form-check-input" name="multi_chk[]"  type="checkbox" value="'.$livemusic->id.'" id="chk_'.$livemusic->id.'" >';
                    return $status;
                })      

                ->addColumn('status', function ($livemusic) {
                    $status = ($livemusic->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('login_type', function ($livemusic) {
                    $loginType = 'NA';
                    if($livemusic->login_type != NULL){
                        $loginType = $livemusic->login_type;
                    }
                    
                    return $loginType;
                })
                ->addColumn('banner_screen', function ($livemusic) {
                    $banner_screen = 'NA';
                    if($livemusic->banner_screen != NULL){
                        $banner_screen = $livemusic->banner_screen;
                    }
                    
                    return $banner_screen;
                })
                ->addColumn('thumbnail_image', function ($livemusic) {
                    $fileURL = config('app.assets_url').$livemusic->banner_name; 
                    $path_info = pathinfo($livemusic->banner_name);
                    if(isset($path_info['extension']) && $path_info['extension'] == 'json'){
                        return 'JSON File<br> <b>'.$fileURL.'</b>';
                    }else{
                        return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($livemusic->id, $livemusic->banner_name) . '</div>';
                    }   
                })
                ->addColumn('updated_at', function ($livemusic) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($livemusic->updated_at));
                    return $updated_at;
                })
                ->addColumn('app_version', function ($livemusic) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    //$circle = $circleList[$livemusic->circle];
                    
                    $versions = 'NA';
                    if($livemusic->app_version != NULL){
                        $versionsArr = explode(',',$livemusic->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('circle', function ($livemusic) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    //$circle = $circleList[$livemusic->circle];
                    
                    $circle = 'NA';
                    if($livemusic->circle != NULL){
                        $circleArr = explode(',',$livemusic->circle);
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
                ->addColumn('link', function ($livemusic) {
                    $actionList = '-';
                    if($livemusic->internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$livemusic->internal_link;
                    }
                    
                    if($livemusic->external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$livemusic->external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($livemusic) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($livemusic->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $livemusic->id . '" data-action="edit" data-id="' . $livemusic->id . '" id="' . $livemusic->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($livemusic->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $livemusic->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })
               ->filter(function ($instance) use ($request) {
                    if ($request->has('banner_screen')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['banner_screen']), strtolower($request->get('banner_screen'))) ? true : false;
                        });
                    }
                    if ($request->has('Livemusic_title')) {
                       $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                           return Str::contains(strtolower($row['Livemusic_title']), strtolower($request->get('Livemusic_title'))) ? true : false;
                       });
                    }
                    if ($request->has('circle') && $request->get('circle')!='0000') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['circle']), strtolower($request->get('circle'))) or Str::contains(($row['circle']), strtolower('0000')) ? true : false;
                        });
                    }
                    if ($request->has('lob') && $request->get('lob')!='Both') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return (Str::contains(strtolower($row['lob']), strtolower($request->get('lob'))) or Str::contains(strtolower($row['lob']), strtolower('Both'))) ? true : false;
                        });
                    }
                    if ($request->has('login_type') && $request->get('login_type') != 'Both') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['login_type']), strtolower($request->get('login_type'))) or Str::contains(strtolower($row['login_type']), strtolower('Both')) ? true : false;
                        });
                    }
                    if ($request->has('brand') && $request->get('brand') !='Brandx') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['brand']), strtolower($request->get('brand'))) or Str::contains(strtolower($row['brand']), strtolower('Brandx')) ? true : false;
                        });
                    }
                    if ($request->has('device_os') && $request->get('device_os') !='Both') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['device_os']), strtolower($request->get('device_os'))) or Str::contains(strtolower($row['device_os']), strtolower('Both')) ? true : false;
                        });
                    }
                    if ($request->has('app_version') && $request->get('app_version') != 'All Versions') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['app_version']), strtolower($request->get('app_version'))) or Str::contains(strtolower($row['app_version']), strtolower('All Versions')) ? true : false;
                        });
                    }
                    if ($request->has('Livemusic_screen')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['Livemusic_screen']), strtolower($request->get('Livemusic_screen'))) ? true : false;
                        });
                    }
                    if ($request->has('Livemusic_rank')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['Livemusic_rank']), strtolower($request->get('Livemusic_rank'))) ? true : false;
                        });
                    }
                    if ($request->has('status')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['status']), strtolower($request->get('status'))) ? true : false;
                        });
                    }
               })
                ->make(true);
    }

    /**
     * Display a form to create new Livemusic.
     *
     * @return view as response
     */
    public function create()
    {
                return view('admin::livemusic.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\LivemusicCreateRequest $request
     * @return json encoded Response
     */
    public function store(LivemusicCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(Livemusic $livemusic)
    {
        $response['success'] = true;
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
        $tabsList = $this->tabRepository->listTabData()->toArray();
        $screenList = ['MusicDasboard' => 'Music Dashboard','LiveMusicEvent'=>'Live Music Event'];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary', 'Both' => 'Both'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $socidList = ['All SOCID' => 'All SOCID', 'COCP' => 'All COCP RED Plans', 'IOIP' => 'All IOIP & INDIVIDUAL RED Plans', '25799385' => 'RED Add-On - 199', '25694515' => 'RED Basic 299', '25694735' => 'RED Entertainment', '25694645' => 'RED Entertainment+', '25904185' => 'RED MAX', '25826505' => 'REDX', '25898675' => 'RED X'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $planList = ['L'=>'Limited','UL'=>'Unlimited'];
        $selectedCirclesArray = [];
        if($livemusic->circle != NULL){
            $selectedCirclesArray = explode(',', $livemusic->circle);
        }
        $selectedAppVersionArray = [];
        if($livemusic->app_version != NULL){
            $selectedAppVersionArray = explode(',', $livemusic->app_version);
        }
        $selectedSocidsArray = [];
        if($livemusic->socid != NULL){
            $selectedSocidsArray = explode(',', $livemusic->socid);
        }
        $selectedpostpaidPersonaArray = [];
        if($livemusic->postpaid_persona != NULL){
            $selectedpostpaidPersonaArray = explode(',', $livemusic->postpaid_persona);
        }

        $selectedRedhierarchyArray = [];
        if($livemusic->red_hierarchy != NULL){
            $selectedRedhierarchyArray = explode(',', $livemusic->red_hierarchy);
        }

        $selectedprepaidPersonaArray = [];
        if($livemusic->prepaid_persona != NULL){
            $selectedprepaidPersonaArray = explode(',', $livemusic->prepaid_persona);
        }
        
        $selectedServiceTypeArray = [];
        if($livemusic->service_type != NULL){
            $selectedServiceTypeArray = explode(',', $livemusic->service_type);
        }
        $response['form'] = view('admin::livemusic.edit', compact('selectedRedhierarchyArray','loginTypeList','serviceTypeList','selectedServiceTypeArray','redHierarchyList','livemusic', 'linkTypeList', 'tabsList', 'selectedCirclesArray', 'osList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedpostpaidPersonaArray', 'selectedprepaidPersonaArray','planList'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\LivemusicUpdateRequest $request, Modules\Admin\Models\Livemusic $livemusic
     * @return json encoded Response
     */
    public function update(LivemusicUpdateRequest $request, Livemusic $livemusic)
    {
        $response = $this->repository->update($request->all(), $livemusic);

        return response()->json($response);
    }
    
}
