<?php
/**
 * The class for managing SpinMaster specific actions.
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
use Modules\Admin\Models\SpinMaster;
use Modules\Admin\Repositories\SpinMasterRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Repositories\TabRepository;
use Modules\Admin\Http\Requests\SpinMasterCreateRequest;
use Modules\Admin\Http\Requests\SpinMasterUpdateRequest;
use Modules\Admin\Http\Requests\SpinMasterDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class SpinMasterController extends Controller
{

    /**
     * The SpinMasterRepository instance.
     *
     * @var Modules\Admin\Repositories\SpinMasterRepository
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
     * Create a new SpinMasterController instance.
     *
     * @param  Modules\Admin\Repositories\SpinMasterRepository $repository
     * @return void
     */
    public function __construct(SpinMasterRepository $repository, AppVersionRepository $appVersionRepository, TabRepository $tabRepository)
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
    public function index(SpinMaster $SpinMaster)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $selectedSocidsArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedServiceTypeArray = [];
        $selectedRedhierarchyArray = [];
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $rewardList = ['1'=>'Telco (data)','2'=>'SDP based subscriptions (Sony LIV, Hungama)','3'=>'Voucher codes','4'=>'Cashback on future recharges (WCS coupon)','5'=>'Physical prizes'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
       // $tabsList = ["Recommended" => 'Recommended','Unlimited' => 'Unlimited', 'All-rounderpacks' => 'All-rounderpacks', 'Internet' => 'Internet', 'Talktime' => 'Talktime', 'SMS' => 'SMS', 'Roaming' => 'Roaming', 'PlanVoucher' => 'PlanVoucher', 'Voice' => 'Voice', 'Game' => 'Game', 'Campaign' => 'Campaign', 'Downtime' => 'Downtime', 'vi_mtv_1' => 'vi_mtv_1', 'vi_mtv_2' => 'vi_mtv_2', 'vi_mtv_3' => 'vi_mtv_3', 'Specific-Screen' => 'Specific-Screen'];
       $tabsList = $this->tabRepository->listTabData()->toArray();
       $screenList = ['Dashboard' => 'Dashboard','InternationalRoaming' => 'International Roaming', 'IdeaTuesday' => 'IdeaTuesday', 'ThankYou' => 'Thank You', 'DashboardWidget' => 'Dashboard Widget', 'DashboardUpperSpinMaster' => 'Dashboard Upper SpinMaster', 'RechargePage' => 'Recharge Page', 'DashboardCenterSpinMaster' => 'Dashboard Center SpinMaster', 'ActivePacksServices' => 'Active Packs & Services', 'Downtime' => 'Downtime', 'FullPageSpinMaster' => 'FullPageSpinMaster', 'ExclusiveOffersSpinMaster' => 'ExclusiveOffersSpinMaster', 'CashbackOffersSpinMaster' => 'CashbackOffersSpinMaster', 'ViRiseSpinMaster' => 'ViRiseSpinMaster','Coupon'=>'Coupon'];

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
        return view('admin::spinmaster.index', compact('loginTypeList','selectedRedhierarchyArray','serviceTypeList','selectedServiceTypeArray','redHierarchyList','linkTypeList','SpinMaster', 'tabsList', 'selectedCirclesArray','rewardList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','planList'));
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
        $SpinMaster = $this->repository->data();
      //  echo '<pre>'; print_r($request); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $SpinMaster = $SpinMaster->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($SpinMaster)

                ->addColumn('checkbox', function ($SpinMaster) {
                    $status = '<input class="form-check-input" name="multi_chk[]"  type="checkbox" value="'.$SpinMaster->id.'" id="chk_'.$SpinMaster->id.'" >';
                    return $status;
                })      

                ->addColumn('status', function ($SpinMaster) {
                    $status = ($SpinMaster->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('reward_type', function ($SpinMaster) {
                    $loginType = 'NA';
                    if($SpinMaster->login_type != NULL){
                        $loginType = $SpinMaster->login_type;
                    }
                    
                    return $loginType;
                })
                ->addColumn('logo_image', function ($SpinMaster) {
                    $fileURL = config('app.assets_url').$SpinMaster->logo_image; 
                    $path_info = pathinfo($SpinMaster->banner_name);
                    if(isset($path_info['extension']) && $path_info['extension'] == 'json'){
                        return 'JSON File<br> <b>'.$fileURL.'</b>';
                    }else{
                        return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($SpinMaster->id, $SpinMaster->logo_image) . '</div>';
                    }   
                })
                ->addColumn('overlay_image', function ($SpinMaster) {
                    $fileURL = config('app.assets_url').$SpinMaster->overlay_image; 
                    $path_info = pathinfo($SpinMaster->banner_name);
                    if(isset($path_info['extension']) && $path_info['extension'] == 'json'){
                        return 'JSON File<br> <b>'.$fileURL.'</b>';
                    }else{
                        return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($SpinMaster->id, $SpinMaster->overlay_image) . '</div>';
                    }   
                })
                ->addColumn('updated_at', function ($SpinMaster) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($SpinMaster->updated_at));
                    return $updated_at;
                })

                ->addColumn('link', function ($SpinMaster) {
                    $actionList = '-';
                    if($SpinMaster->internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$SpinMaster->internal_link;
                    }
                    
                    if($SpinMaster->external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$SpinMaster->external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($SpinMaster) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($SpinMaster->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $SpinMaster->id . '" data-action="edit" data-id="' . $SpinMaster->id . '" id="' . $SpinMaster->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($SpinMaster->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $SpinMaster->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })

               ->filter(function ($instance) use ($request) {
                    // if ($request->has('title')) {
                    //    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //        return Str::contains(strtolower($row['title']), strtolower($request->get('title'))) ? true : false;
                    //    });
                    // }
                    // if ($request->has('circle') && $request->get('circle')!='0000') {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['circle']), strtolower($request->get('circle'))) or Str::contains(($row['circle']), strtolower('0000')) ? true : false;
                    //     });
                    // }
                    // if ($request->has('lob') && $request->get('lob')!='Both') {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return (Str::contains(strtolower($row['lob']), strtolower($request->get('lob'))) or Str::contains(strtolower($row['lob']), strtolower('Both'))) ? true : false;
                    //     });
                    // }
                    // if ($request->has('login_type') && $request->get('login_type') != 'Both') {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['login_type']), strtolower($request->get('login_type'))) or Str::contains(strtolower($row['login_type']), strtolower('Both')) ? true : false;
                    //     });
                    // }
                    // if ($request->has('brand') && $request->get('brand') !='Brandx') {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['brand']), strtolower($request->get('brand'))) or Str::contains(strtolower($row['brand']), strtolower('Brandx')) ? true : false;
                    //     });
                    // }
                    // if ($request->has('device_os') && $request->get('device_os') !='Both') {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['device_os']), strtolower($request->get('device_os'))) or Str::contains(strtolower($row['device_os']), strtolower('Both')) ? true : false;
                    //     });
                    // }
                    // if ($request->has('app_version') && $request->get('app_version') != 'All Versions') {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['app_version']), strtolower($request->get('app_version'))) or Str::contains(strtolower($row['app_version']), strtolower('All Versions')) ? true : false;
                    //     });
                    // }
                    // if ($request->has('SpinMaster_screen')) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['SpinMaster_screen']), strtolower($request->get('SpinMaster_screen'))) ? true : false;
                    //     });
                    // }
                    // if ($request->has('SpinMaster_rank')) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['SpinMaster_rank']), strtolower($request->get('SpinMaster_rank'))) ? true : false;
                    //     });
                    // }
                    // if ($request->has('status')) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return Str::contains(strtolower($row['status']), strtolower($request->get('status'))) ? true : false;
                    //     });
                    // }
               })
                ->make(true);
    }

    /**
     * Display a form to create new SpinMaster.
     *
     * @return view as response
     */
    public function create()
    {
                return view('admin::spinmaster.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\SpinMasterCreateRequest $request
     * @return json encoded Response
     */
    public function store(SpinMasterCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(SpinMaster $SpinMaster)
    {
        $response['success'] = true;
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $rewardList = ['1'=>'Telco (data)','2'=>'SDP based subscriptions (Sony LIV, Hungama)','3'=>'Voucher codes','4'=>'Cashback on future recharges (WCS coupon)','5'=>'Physical prizes'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
        $tabsList = $this->tabRepository->listTabData()->toArray();
        $screenList = ['Dashboard' => 'Dashboard','InternationalRoaming' => 'International Roaming', 'IdeaTuesday' => 'IdeaTuesday', 'ThankYou' => 'Thank You', 'DashboardWidget' => 'Dashboard Widget', 'DashboardUpperSpinMaster' => 'Dashboard Upper SpinMaster', 'RechargePage' => 'Recharge Page', 'DashboardCenterSpinMaster' => 'Dashboard Center SpinMaster', 'ActivePacksServices' => 'Active Packs & Services', 'Downtime' => 'Downtime', 'FullPageSpinMaster' => 'FullPageSpinMaster', 'ExclusiveOffersSpinMaster' => 'ExclusiveOffersSpinMaster', 'CashbackOffersSpinMaster' => 'CashbackOffersSpinMaster', 'ViRiseSpinMaster' => 'ViRiseSpinMaster','Coupon'=>'Coupon'];
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
        if($SpinMaster->circle != NULL){
            $selectedCirclesArray = explode(',', $SpinMaster->circle);
        }
        $selectedAppVersionArray = [];
        if($SpinMaster->app_version != NULL){
            $selectedAppVersionArray = explode(',', $SpinMaster->app_version);
        }
        $selectedSocidsArray = [];
        if($SpinMaster->socid != NULL){
            $selectedSocidsArray = explode(',', $SpinMaster->socid);
        }
        $selectedpostpaidPersonaArray = [];
        if($SpinMaster->postpaid_persona != NULL){
            $selectedpostpaidPersonaArray = explode(',', $SpinMaster->postpaid_persona);
        }

        $selectedRedhierarchyArray = [];
        if($SpinMaster->red_hierarchy != NULL){
            $selectedRedhierarchyArray = explode(',', $SpinMaster->red_hierarchy);
        }

        $selectedprepaidPersonaArray = [];
        if($SpinMaster->prepaid_persona != NULL){
            $selectedprepaidPersonaArray = explode(',', $SpinMaster->prepaid_persona);
        }
        
        $selectedServiceTypeArray = [];
        if($SpinMaster->service_type != NULL){
            $selectedServiceTypeArray = explode(',', $SpinMaster->service_type);
        }
        $response['form'] = view('admin::spinmaster.edit', compact('selectedRedhierarchyArray','loginTypeList','serviceTypeList','selectedServiceTypeArray','redHierarchyList','SpinMaster', 'linkTypeList', 'tabsList', 'selectedCirclesArray', 'rewardList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedpostpaidPersonaArray', 'selectedprepaidPersonaArray','planList'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\SpinMasterUpdateRequest $request, Modules\Admin\Models\SpinMaster $SpinMaster
     * @return json encoded Response
     */
    public function update(SpinMasterUpdateRequest $request, SpinMaster $SpinMaster)
    {
        $response = $this->repository->update($request->all(), $SpinMaster);

        return response()->json($response);
    }
    
}
