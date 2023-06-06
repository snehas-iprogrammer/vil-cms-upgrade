<?php
/**
 * The class for managing Banner specific actions.
 *
 *
 * @author Sneha Shete <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Admin\Models\SocialGamingBanner;
use Modules\Admin\Repositories\SocialGamingBannerRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Repositories\GameScreenRepository;
use Modules\Admin\Http\Requests\SocialGamingBannerCreateRequest;
use Modules\Admin\Http\Requests\SocialGamingBannerUpdateRequest;
use Modules\Admin\Http\Requests\SocialGamingBannerDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class SocialGamingBannerController extends Controller
{

    /**
     * The BannerRepository instance.
     *
     * @var Modules\Admin\Repositories\SocialGamingBannerRepository
     */
    protected $repository;
    /**
    * The CountryRepository instance.
    *
    * @var Modules\Admin\Repositories\AppVersionRepository
    */
   protected $appVersionRepository;
   protected $gameScreenRepository;

    /**
     * Create a new BannerController instance.
     *
     * @param  Modules\Admin\Repositories\SocialGamingBannerRepository $repository
     * @return void
     */
    public function __construct(SocialGamingBannerRepository $repository, AppVersionRepository $appVersionRepository,GameScreenRepository $gameScreenRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->appVersionRepository = $appVersionRepository;
        $this->gameScreenRepository = $gameScreenRepository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(SocialGamingBanner $socialbanner)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $selectedSocidsArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedServiceTypeArray = [];
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $categoryList = [];
        $catList = $this->repository->listCategory();
        if(!empty($catList)){
            $catList = array_column($catList,'name');
            foreach($catList as $key => $val) {
                $categoryList[$val]=$val;
            }
        }
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
        $screenList = $this->gameScreenRepository->listGameScreenData('vi')->toArray();
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $planList = ['L'=>'Limited','UL'=>'Unlimited'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $socidList = ['All SOCID' => 'All SOCID', 'COCP' => 'All COCP RED Plans', 'IOIP' => 'All IOIP & INDIVIDUAL RED Plans', '25799385' => 'RED Add-On - 199', '25694515' => 'RED Basic 299', '25694735' => 'RED Entertainment', '25694645' => 'RED Entertainment+', '25904185' => 'RED MAX', '25826505' => 'REDX', '25898675' => 'RED X'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        return view('admin::socialgaming-banners.index', compact('categoryList','serviceTypeList','selectedServiceTypeArray','redHierarchyList','linkTypeList','socialbanner', 'selectedCirclesArray','osList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','planList'));
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
        $socialbanners = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $socialbanners = $socialbanners->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });

        }

        return Datatables::of($socialbanners)

                ->addColumn('banner_check', function ($socialbanners) {
                    $status = '<input class="form-check-input" name="multi_chk[]"  type="checkbox" value="'.$socialbanners->id.'" id="chk_'.$socialbanners->id.'" >';
                    return $status;
                })      

                ->addColumn('status', function ($socialbanners) {
                    $status = ($socialbanners->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('login_type', function ($socialbanner) {
                    $loginType = 'NA';
                    if($socialbanner->login_type != NULL){
                        $loginType = $socialbanner->login_type;
                    }
                    
                    return $loginType;
                })
                ->addColumn('thumbnail_image', function ($socialbanners) {
                    $fileURL = config('app.assets_url').$socialbanners->banner_name; 
                    $path_info = pathinfo($socialbanners->banner_name);
                    if(isset($path_info['extension']) && $path_info['extension'] == 'json'){
                        return 'JSON File<br> <b>'.$fileURL.'</b>';
                    }else{
                        return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($socialbanners->id, $socialbanners->banner_name) . '</div>';
                    }   
                })
                ->addColumn('updated_at', function ($socialbanner) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($socialbanner->updated_at));
                    return $updated_at;
                })
                ->addColumn('app_version', function ($socialbanner) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $versions = 'NA';
                    if($socialbanner->app_version != NULL){
                        $versionsArr = explode(',',$socialbanner->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('circle', function ($socialbanner) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $circle = 'NA';
                    if($socialbanner->circle != NULL){
                        $circleArr = explode(',',$socialbanner->circle);
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
                ->addColumn('link', function ($socialbanner) {
                    $actionList = '-';
                    if($socialbanner->internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$socialbanner->internal_link;
                    }
                    
                    if($socialbanner->external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$socialbanner->external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($socialbanner) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($socialbanner->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $socialbanner->id . '" data-action="edit" data-id="' . $socialbanner->id . '" id="' . $socialbanner->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($socialbanner->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $socialbanner->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })
               ->filter(function ($instance) use ($request) {

                   
                    if ($request->has('banner_title')) {
                       $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                           return Str::contains(strtolower($row['banner_title']), strtolower($request->get('banner_title'))) ? true : false;
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
                    if ($request->has('banner_screen')) {
                       
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['screen_id']), strtolower($request->get('banner_screen'))) ? true : false;
                        });
                    }
                    if ($request->has('banner_rank')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['banner_rank']), strtolower($request->get('banner_rank'))) ? true : false;
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
     * Display a form to create new banner.
     *
     * @return view as response
     */
    public function create()
    {

        return view('admin::socialgaming-banners.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\SocialGamingBannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(SocialGamingBannerCreateRequest $request)
    {
       
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(SocialGamingBanner $socialbanner)
    {
        $response['success'] = true;
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        //$screenList = ['upperGameBanner' => 'Upper Game Banner','featuredGameBanner' => 'Featured Game Banner', 'trendingNowGameBanner' => 'Trending Now Game Banner', 'socialGamingBanner' => 'Social Gaming Banner', 'bottomGameBanner' => 'Bottom Game Banner','dashboardGameBanner' => 'Dashboard Game Banner'];
        $screenList = $this->gameScreenRepository->listGameScreenData('vi')->toArray();
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $prepaidPersonaList = ['All' => 'All', 'Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['All' => 'All', 'COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $socidList = ['All SOCID' => 'All SOCID', 'COCP' => 'All COCP RED Plans', 'IOIP' => 'All IOIP & INDIVIDUAL RED Plans', '25799385' => 'RED Add-On - 199', '25694515' => 'RED Basic 299', '25694735' => 'RED Entertainment', '25694645' => 'RED Entertainment+', '25904185' => 'RED MAX', '25826505' => 'REDX', '25898675' => 'RED X'];
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
       // $categoryList = ["Gold" => 'Gold','Platinum' => 'Platinum', 'Freemium' => 'Freemium'];
        $categoryList = [];
        $catList = $this->repository->listCategory();
        if(!empty($catList)){
            $catList = array_column($catList,'name');
            foreach($catList as $key => $val) {
                $categoryList[$val]=$val;
            }
        }
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $selectedCirclesArray = [];
        if($socialbanner->circle != NULL){
            $selectedCirclesArray = explode(',', $socialbanner->circle);
        }
        $selectedAppVersionArray = [];
        if($socialbanner->app_version != NULL){
            $selectedAppVersionArray = explode(',', $socialbanner->app_version);
        }
        $selectedSocidsArray = [];
        if($socialbanner->socid != NULL){
            $selectedSocidsArray = explode(',', $socialbanner->socid);
        }
        $selectedpostpaidPersonaArray = [];
        if($socialbanner->postpaid_persona != NULL){
            $selectedpostpaidPersonaArray = explode(',', $socialbanner->postpaid_persona);
        }
        $selectedprepaidPersonaArray = [];
        if($socialbanner->prepaid_persona != NULL){
            $selectedprepaidPersonaArray = explode(',', $socialbanner->prepaid_persona);
        }
        
        $selectedServiceTypeArray = [];
        if($socialbanner->service_type != NULL){
            $selectedServiceTypeArray = explode(',', $socialbanner->service_type);
        }
        $response['form'] = view('admin::socialgaming-banners.edit', compact('categoryList','redHierarchyList','socialbanner','selectedCirclesArray', 'osList', 'screenList', 'rankList', 'circleList', 'lobList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedpostpaidPersonaArray', 'selectedprepaidPersonaArray'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\SocialGamingBannerUpdateRequest $request, Modules\Admin\Models\Banner $socialbanner
     * @return json encoded Response
     */
    public function update(SocialGamingBannerUpdateRequest $request, SocialGamingBanner $socialbanner)
    {
        $response = $this->repository->update($request->all(), $socialbanner);

        return response()->json($response);
    }
    
}
