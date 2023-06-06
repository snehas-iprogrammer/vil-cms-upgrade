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
use Modules\Admin\Models\Banner;
use Modules\Admin\Repositories\BannerRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Repositories\TabRepository;
use Modules\Admin\Http\Requests\BannerCreateRequest;
use Modules\Admin\Http\Requests\BannerUpdateRequest;
use Modules\Admin\Http\Requests\BannerDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class BannerController extends Controller
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
    * The tabRepository instance.
    *
    * @var Modules\Admin\Repositories\TabRepository
    */
    protected $tabRepository;

    /**
     * Create a new BannerController instance.
     *
     * @param  Modules\Admin\Repositories\BannerRepository $repository
     * @return void
     */
    public function __construct(BannerRepository $repository, AppVersionRepository $appVersionRepository, TabRepository $tabRepository)
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
    public function index(Banner $banner)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $selectedSocidsArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $hiddenCircleArray = $hiddenBannerScreenArray ='';
        $selectedServiceTypeArray = $selectedredHierarchyList = [];
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
        $tabsList = $this->tabRepository->listTabData()->toArray();
        $screenList = $this->repository->bannerScreenData()->toArray();
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
        return view('admin::banners.index', compact('loginTypeList','serviceTypeList','selectedredHierarchyList','selectedServiceTypeArray','redHierarchyList','linkTypeList','banner', 'tabsList', 'selectedCirclesArray','osList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','planList','hiddenCircleArray','hiddenBannerScreenArray'));
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
        $banners = $this->repository->data();
       // echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $banners = $banners->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($banners)

                ->addColumn('banner_check', function ($banners) {
                    $status = '<input class="form-check-input" name="multi_chk[]"  type="checkbox" value="'.$banners->id.'" id="chk_'.$banners->id.'" >';
                    return $status;
                })      

                ->addColumn('status', function ($banners) {
                    $status = ($banners->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('login_type', function ($banner) {
                    $loginType = 'NA';
                    if($banner->login_type != NULL){
                        $loginType = $banner->login_type;
                    }
                    
                    return $loginType;
                })
                ->addColumn('banner_screen', function ($banner) {
                    return $banner->screen_title;
                })                
                ->addColumn('thumbnail_image', function ($banners) {
                    $fileURL = config('app.assets_url').$banners->banner_name; 
                    $path_info = pathinfo($banners->banner_name);
                    if(isset($path_info['extension']) && $path_info['extension'] == 'json'){
                        return 'JSON File<br> <b>'.$fileURL.'</b>';
                    }else{
                        return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($banners->id, $banners->banner_name) . '</div>';
                    }   
                })
                ->addColumn('updated_at', function ($banner) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($banner->updated_at));
                    return $updated_at;
                })
                ->addColumn('app_version', function ($banner) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    //$circle = $circleList[$banner->circle];
                    
                    $versions = 'NA';
                    if($banner->app_version != NULL){
                        $versionsArr = explode(',',$banner->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('circle', function ($banner) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    //$circle = $circleList[$banner->circle];
                    
                    $circle = 'NA';
                    if($banner->circle != NULL){
                        $circleArr = explode(',',$banner->circle);
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
                ->addColumn('link', function ($banner) {
                    $actionList = '-';
                    if($banner->internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$banner->internal_link;
                    }
                    
                    if($banner->external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$banner->external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($banner) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($banner->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $banner->id . '" data-action="edit" data-id="' . $banner->id . '" id="' . $banner->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($banner->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $banner->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
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
                    if ($request->has('banner_screen')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            //return Str::contains(strtolower($row['banner_screen']), strtolower($request->get('banner_screen'))) ? true : false;
                            return strcmp(strtolower($row['banner_screen']), strtolower($request->get('banner_screen'))) == 0 ? true : false;
                             
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

        return view('admin::banners.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(BannerCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(Banner $banner)
    {
        $response['success'] = true;
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $serviceTypeList = ['DXL' => 'DXL','EAI' => 'EAI','SR' => 'SR','ETopUp' => 'ETopUp','EBPP' => 'EBPP'];
        $tabsList = $this->tabRepository->listTabData()->toArray();
        $screenList = $this->repository->bannerScreenData()->toArray();
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
        $selectedCirclesArray = $selectedredHierarchyList = $hiddenCircleArray=[];
        if($banner->circle != NULL){
            $hiddenCircleArray = $banner->circle;
            $selectedCirclesArray = explode(',', $banner->circle);
        }
        $selectedAppVersionArray = [];
        if($banner->app_version != NULL){
            $selectedAppVersionArray = explode(',', $banner->app_version);
        }
        $selectedSocidsArray = [];
        if($banner->socid != NULL){
            $selectedSocidsArray = explode(',', $banner->socid);
        }
        $selectedpostpaidPersonaArray = [];
        if($banner->postpaid_persona != NULL){
            $selectedpostpaidPersonaArray = explode(',', $banner->postpaid_persona);
        }
        $selectedprepaidPersonaArray = [];
        if($banner->prepaid_persona != NULL){
            $selectedprepaidPersonaArray = explode(',', $banner->prepaid_persona);
        }
        
        $selectedServiceTypeArray = [];
        if($banner->service_type != NULL){
            $selectedServiceTypeArray = explode(',', $banner->service_type);
        }

        $selectedredHierarchyList = [];
        if($banner->red_hierarchy != NULL){
            $selectedredHierarchyList = explode(',', $banner->red_hierarchy);
        }

        $hiddenBannerScreenArray ='';
        if($banner->red_hierarchy != NULL){
            $hiddenBannerScreenArray = $banner->banner_screen;
        }

        $response['form'] = view('admin::banners.edit', compact('loginTypeList','serviceTypeList','selectedredHierarchyList','selectedServiceTypeArray','redHierarchyList','banner', 'linkTypeList', 'tabsList', 'selectedCirclesArray', 'osList', 'screenList', 'rankList', 'circleList', 'lobList', 'brandList','appVersionList','selectedAppVersionArray', 'prepaidPersonaList', 'postpaidPersonaList', 'socidList', 'selectedSocidsArray', 'socidIncludeExcludeList', 'selectedpostpaidPersonaArray', 'selectedprepaidPersonaArray','planList','hiddenCircleArray','hiddenBannerScreenArray'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        $response = $this->repository->update($request->all(), $banner);

        return response()->json($response);
    }
    
}
