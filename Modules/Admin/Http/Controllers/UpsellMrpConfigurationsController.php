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
use Modules\Admin\Models\UpsellMrpConfigurations;
use Modules\Admin\Repositories\UpsellMrpConfigurationsRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\UpsellMrpConfigurationsCreateRequest;
use Modules\Admin\Http\Requests\UpsellMrpConfigurationsUpdateRequest;
use Modules\Admin\Http\Requests\UpsellMrpConfigurationsDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class UpsellMrpConfigurationsController extends Controller
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
    public function __construct(UpsellMrpConfigurationsRepository $repository, AppVersionRepository $appVersionRepository)
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
    public function index(UpsellMrpConfigurations $upsellMrpConfigurations)
    {
        $selectedCirclesArray = [];
        $selectedAppVersionArray = [];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $categoryList = ["Unlimited" => 'Unlimited','Internet' => 'Internet', 'All-rounderpacks' => 'All-rounderpacks'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        return view('admin::upsell-mrp-configurations.index', compact('categoryList','circleList', 'selectedCirclesArray','appVersionList','selectedAppVersionArray'));
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
            $response['message'] = trans('admin::messages.deleted', ['name' => 'Payment Banner']);
        } else {
            $response['status'] = 'fail';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Payment Banner']);
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
        $upsellMrpConfigurations = $this->repository->data();
        //echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $upsellMrpConfigurations = $upsellMrpConfigurations->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($upsellMrpConfigurations)
                ->addColumn('status', function ($upsellMrpConfigurations) {
                    $status = ($upsellMrpConfigurations->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('thumbnail_image', function ($upsellMrpConfigurations) {
                    return '<div class="testimonial-listing-img">' . ImageHelper::getPaymentBannerImagePath($upsellMrpConfigurations->id, $upsellMrpConfigurations->image) . '</div>';
                })
                ->addColumn('created_at', function ($upsellMrpConfigurations) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $created_at = date($dateTimeFormat, strtotime($upsellMrpConfigurations->created_at));
                    return $created_at;
                })   
                ->addColumn('circle', function ($upsellMrpConfigurations) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $circle = 'NA';
                    if($upsellMrpConfigurations->circle != NULL){
                        $circleArr = explode(',',$upsellMrpConfigurations->circle);
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
                ->addColumn('action', function ($upsellMrpConfigurations) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($upsellMrpConfigurations->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $upsellMrpConfigurations->id . '" data-action="edit" data-id="' . $upsellMrpConfigurations->id . '" id="' . $upsellMrpConfigurations->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($upsellMrpConfigurations->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $upsellMrpConfigurations->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
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

        return view('admin::upsell-mrp-configurations.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(Request $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(UpsellMrpConfigurations $upsellMrpConfigurations)
    {
        $categoryList = ["Unlimited" => 'Unlimited','Internet' => 'Internet', 'All-rounderpacks' => 'All-rounderpacks'];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $selectedCirclesArray = [];
        if($upsellMrpConfigurations->circle != NULL){
            $selectedCirclesArray = explode(',', $upsellMrpConfigurations->circle);
        }
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $selectedAppVersionArray = [];
        if($upsellMrpConfigurations->app_version != NULL){
            $selectedAppVersionArray = explode(',', $upsellMrpConfigurations->app_version);
        }
        $response['success'] = true;
        $response['form'] = view('admin::upsell-mrp-configurations.edit', compact('upsellMrpConfigurations', 'categoryList', 'circleList', 'selectedCirclesArray','appVersionList','selectedAppVersionArray'))->render();
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(Request $request, UpsellMrpConfigurations $upsellMrpConfigurations)
    {
        $response = $this->repository->update($request->all(), $upsellMrpConfigurations);

        return response()->json($response);
    }
    
}
