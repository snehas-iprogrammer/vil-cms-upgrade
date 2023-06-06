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
use Modules\Admin\Models\AppQuickLinks;
use Modules\Admin\Repositories\MasterQuickLinkRepository;
use Modules\Admin\Repositories\AppQuickLinksRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\AppQuickLinksCreateRequest;
use Modules\Admin\Http\Requests\AppQuickLinksUpdateRequest;
use Modules\Admin\Http\Requests\AppQuickLinksDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class AppQuickLinksController extends Controller
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
    public function __construct(AppQuickLinksRepository $repository, AppVersionRepository $appVersionRepository , MasterQuickLinkRepository $masterQuickLinkRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->appVersionRepository = $appVersionRepository;
        $this->masterQuickLinkRepository = $masterQuickLinkRepository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(AppQuickLinks $appQuickLinks)
    {
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedSocidsArray = [];
        $selectedAppVersionArray = [];
        $selectedRedHierarchyArray = [];
        $selectedMasterQuickLinkArray = [];
        $selectedCirclesArray = [];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $redHierarchyList = ["All" => 'All',"primary" => 'Primary','secondary' => 'Secondary', 'individual' => 'Individual'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary','Both' => "Both"];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $planList = ['UL' => 'UL', 'L' => 'L','Both'=>'Both'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid','Both' => "Both"];
        $prepaidPersonaList = ['Youth' => 'Youth', 'Nonyouth' => 'Nonyouth','All'=>'All'];
        $postpaidPersonaList = ['COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual','All'=>'All'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $masterQuickLinkList = $this->masterQuickLinkRepository->listMasterQuickLinkData()->toArray();
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        return view('admin::appquick-links.index', compact('selectedRedHierarchyArray','osList','brandList','redHierarchyList','selectedSocidsArray','socidIncludeExcludeList','appVersionList', 'selectedAppVersionArray', 'planList', 'loginTypeList', 'lobList', 'prepaidPersonaList', 'postpaidPersonaList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','masterQuickLinkList','selectedMasterQuickLinkArray','rankList','circleList','selectedCirclesArray'));
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
            $response['message'] = trans('admin::messages.deleted', ['name' => 'Quick Link']);
        } else {
            $response['status'] = 'fail';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Quick Link']);
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
        $appQuickLinks = $this->repository->data();
        //echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $appQuickLinks = $appQuickLinks->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($appQuickLinks)
                ->addColumn('status', function ($banners) {
                    $status = ($banners->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                // ->addColumn('socid', function ($appQuickLinks) {
                //     if($appQuickLinks->socid_include_exclude == 1){
                //         $socidData = '<b>Include</b> <br>'.$appQuickLinks->socid;
                //     }else{
                //         $socidData = '<b>Exclude</b> <br>'.$appQuickLinks->socid;
                //     }
                    
                //     return $socidData;
                // })
                ->addColumn('app_version', function ($appQuickLinks) {
                     $versions = 'NA';
                    if($appQuickLinks->app_version != NULL){
                        $versionsArr = explode(',',$appQuickLinks->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('circle', function ($banner) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
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

                ->addColumn('updated_at', function ($otherbanners) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $created_at = date($dateTimeFormat, strtotime($otherbanners->updated_at));
                    return $created_at;
                })
                ->addColumn('action', function ($appQuickLinks) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($appQuickLinks->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $appQuickLinks->id . '" data-action="edit" data-id="' . $appQuickLinks->id . '" id="' . $appQuickLinks->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($appQuickLinks->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $appQuickLinks->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
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
                    if ($request->has('title')) {
                       $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                           return Str::contains(strtolower($row['title']), strtolower($request->get('title'))) ? true : false;
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
                    if ($request->has('login') && $request->get('login') != 'Both') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['login']), strtolower($request->get('login'))) or Str::contains(strtolower($row['login']), strtolower('Both')) ? true : false;
                        });
                    }
                   
                    if ($request->has('app_version') && $request->get('app_version') != 'All Versions') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['app_version']), strtolower($request->get('app_version'))) or Str::contains(strtolower($row['app_version']), strtolower('All Versions')) ? true : false;
                        });
                    }
                    if ($request->has('prepaid_persona') && $request->get('prepaid_persona') != 'All') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['prepaid_persona']), strtolower($request->get('prepaid_persona'))) or Str::contains(strtolower($row['prepaid_persona']), strtolower('All')) ? true : false;
                        });
                    }
                    if ($request->has('postpaid_persona') && $request->get('postpaid_persona') != 'All') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['postpaid_persona']), strtolower($request->get('postpaid_persona'))) or Str::contains(strtolower($row['postpaid_persona']), strtolower('All')) ? true : false;
                        });
                    }
                    if ($request->has('plan') && $request->get('plan') != 'Both') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['plan']), strtolower($request->get('plan'))) or Str::contains(strtolower($row['plan']), strtolower('Both')) ? true : false;
                        });
                    }
                    
                    if ($request->has('rank')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['rank']), strtolower($request->get('rank'))) ? true : false;
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

        return view('admin::appquick-links.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(AppQuickLinksCreateRequest $request)
    {
        //echo '<pre>'; print_r($request->all()); die;
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(AppQuickLinks $appQuickLinks)
    {
        $response['success'] = true;
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary','Both' => "Both"];
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $planList = ['UL' => 'UL', 'L' => 'L','Both' => 'Both'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid','Both' => "Both"];
        $prepaidPersonaList = ['Youth' => 'Youth', 'Nonyouth' => 'Nonyouth','All'=>'All'];
        $postpaidPersonaList = ['COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual','All'=>'All'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $masterQuickLinkList = $this->masterQuickLinkRepository->listMasterQuickLinkData()->toArray();
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $brandList = ['Idea' => 'Idea', 'Vodafone' => 'Vodafone', 'Brandx' => 'Brandx'];
        $selectedCirclesArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedAppVersionArray = [];
        $selectedRedHierarchyArray = [];
        $selectedMasterQuickLinkArray = [];
        $redHierarchyList = ["All" => 'All',"primary" => 'Primary','secondary' => 'Secondary', 'individual' => 'Individual'];
        if($appQuickLinks->red_hierarchy != NULL){
            $selectedRedHierarchyArray = explode(',', $appQuickLinks->red_hierarchy);
        }
        
        if($appQuickLinks->app_version != NULL){
            $selectedAppVersionArray = explode(',', $appQuickLinks->app_version);
        }

        if($appQuickLinks->circle != NULL){
            $selectedCirclesArray = explode(',', $appQuickLinks->circle);
        }

        if($appQuickLinks->lob !='Both'){
            if($appQuickLinks->lob == 'Prepaid'){
                if($appQuickLinks->prepaid_persona != NULL){
                    $selectedprepaidPersonaArray = explode(',', $appQuickLinks->prepaid_persona);
                }            
            }else{
                if($appQuickLinks->postpaid_persona != NULL){
                    $selectedpostpaidPersonaArray = explode(',', $appQuickLinks->postpaid_persona);
                }
            }
        }else{
            
                if($appQuickLinks->prepaid_persona != NULL){
                    $selectedprepaidPersonaArray = explode(',', $appQuickLinks->prepaid_persona);
                }            
            
                if($appQuickLinks->postpaid_persona != NULL){
                    $selectedpostpaidPersonaArray = explode(',', $appQuickLinks->postpaid_persona);
                }
            
        }

        if($appQuickLinks->quicklink_id != NULL){
            $selectedMasterQuickLinkArray = explode(',', $appQuickLinks->quicklink_id);
        }
        
        $selectedSocidsArray = [];
        if($appQuickLinks->socid != NULL){
            $selectedSocidsArray = explode(',', $appQuickLinks->socid);
        }
        
        $response['form'] = view('admin::appquick-links.edit', compact('selectedRedHierarchyArray','osList','brandList','redHierarchyList','selectedSocidsArray','socidIncludeExcludeList','appQuickLinks','appVersionList', 'selectedAppVersionArray', 'planList', 'loginTypeList', 'lobList', 'prepaidPersonaList', 'postpaidPersonaList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray','selectedMasterQuickLinkArray','masterQuickLinkList','rankList','circleList','selectedCirclesArray'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(AppQuickLinksUpdateRequest $request, AppQuickLinks $appQuickLinks)
    {
        $response = $this->repository->update($request->all(), $appQuickLinks);

        return response()->json($response);
    }
    
}
