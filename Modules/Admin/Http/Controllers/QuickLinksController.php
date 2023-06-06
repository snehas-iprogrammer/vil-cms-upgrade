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
use Modules\Admin\Models\QuickLinks;
use Modules\Admin\Repositories\QuickLinksRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\QuickLinksCreateRequest;
use Modules\Admin\Http\Requests\QuickLinksUpdateRequest;
use Modules\Admin\Http\Requests\QuickLinksDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class QuickLinksController extends Controller
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
    public function __construct(QuickLinksRepository $repository, AppVersionRepository $appVersionRepository)
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
    public function index(QuickLinks $quickLinks)
    {
        $selectedprepaidPersonaArray = [];
        $selectedpostpaidPersonaArray = [];
        $selectedSocidsArray = [];
        $selectedAppVersionArray = [];
        $selectedRedHierarchyArray = [];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary'];
        $planList = ['UL' => 'UL', 'L' => 'L'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid'];
        $prepaidPersonaList = ['Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        return view('admin::quick-links.index', compact('selectedRedHierarchyArray','redHierarchyList','selectedSocidsArray','socidIncludeExcludeList','appVersionList', 'selectedAppVersionArray', 'planList', 'loginTypeList', 'lobList', 'prepaidPersonaList', 'postpaidPersonaList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray'));
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
        $quickLinks = $this->repository->data();
        //echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $quickLinks = $quickLinks->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($quickLinks)
                ->addColumn('status', function ($banners) {
                    $status = ($banners->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('socid', function ($quickLinks) {
                    if($quickLinks->socid_include_exclude == 1){
                        $socidData = '<b>Include</b> <br>'.$quickLinks->socid;
                    }else{
                        $socidData = '<b>Exclude</b> <br>'.$quickLinks->socid;
                    }
                    
                    return $socidData;
                })
                ->addColumn('app_version', function ($quickLinks) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $versions = 'NA';
                    if($quickLinks->app_version != NULL){
                        $versionsArr = explode(',',$quickLinks->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    
                    return $versions;
                })
                ->addColumn('updated_at', function ($otherbanners) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $created_at = date($dateTimeFormat, strtotime($otherbanners->updated_at));
                    return $created_at;
                })
                ->addColumn('action', function ($quickLinks) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($quickLinks->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $quickLinks->id . '" data-action="edit" data-id="' . $quickLinks->id . '" id="' . $quickLinks->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($quickLinks->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $quickLinks->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
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

        return view('admin::quick-links.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(QuickLinksCreateRequest $request)
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
    public function edit(QuickLinks $quickLinks)
    {
        $response['success'] = true;
        $loginTypeList = ['Primary' => 'Primary', 'Secondary' => 'Secondary'];
        $planList = ['UL' => 'UL', 'L' => 'L'];
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid'];
        $prepaidPersonaList = ['Youth' => 'Youth', 'Nonyouth' => 'Nonyouth'];
        $postpaidPersonaList = ['COCP' => 'COCP', 'IOIP' => 'IOIP', 'COIP' => 'COIP', 'Individual' => 'Individual'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $socidIncludeExcludeList = ['1' => 'Include Socid', '2' => 'Exclude Socid'];
        $selectedpostpaidPersonaArray = [];
        $selectedprepaidPersonaArray = [];
        $selectedAppVersionArray = [];
        $selectedRedHierarchyArray = [];
        $redHierarchyList = ["All" => 'All',"Primary" => 'Primary','Secondary' => 'Secondary', 'Individual' => 'Individual'];
        if($quickLinks->red_hierarchy != NULL){
            $selectedRedHierarchyArray = explode(',', $quickLinks->red_hierarchy);
        }
        
        if($quickLinks->app_version != NULL){
            $selectedAppVersionArray = explode(',', $quickLinks->app_version);
        }
        
        if($quickLinks->lob == 'Prepaid'){
            if($quickLinks->persona != NULL){
                $selectedprepaidPersonaArray = explode(',', $quickLinks->persona);
            }            
        }else{
            if($quickLinks->persona != NULL){
                $selectedpostpaidPersonaArray = explode(',', $quickLinks->persona);
            }
        }
        
        $selectedSocidsArray = [];
        if($quickLinks->socid != NULL){
            $selectedSocidsArray = explode(',', $quickLinks->socid);
        }
        
        $response['form'] = view('admin::quick-links.edit', compact('selectedRedHierarchyArray','redHierarchyList','selectedSocidsArray','socidIncludeExcludeList','quickLinks','appVersionList', 'selectedAppVersionArray', 'planList', 'loginTypeList', 'lobList', 'prepaidPersonaList', 'postpaidPersonaList', 'selectedprepaidPersonaArray', 'selectedpostpaidPersonaArray'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(QuickLinksUpdateRequest $request, QuickLinks $quickLinks)
    {
        $response = $this->repository->update($request->all(), $quickLinks);

        return response()->json($response);
    }
    
}
