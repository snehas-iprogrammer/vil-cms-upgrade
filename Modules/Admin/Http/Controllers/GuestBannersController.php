<?php
/**
 * The class for managing faq categories specific actions.
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
use Modules\Admin\Models\GuestBanners;
use Modules\Admin\Repositories\GuestBannerConfigRepository;
use Modules\Admin\Repositories\GuestBannersRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Http\Requests\GuestBannersCreateRequest;
use Modules\Admin\Http\Requests\GuestBannersUpdateRequest;
use Modules\Admin\Http\Requests\GuestBannersDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;



class GuestBannersController extends Controller
{

    /**
     * The FaqCategoryRepository instance.
     *
     * @var Modules\Admin\Repositories\FaqCategoryRepository
     */
    protected $repository;
    protected $guestBannerConfigRepository;


    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\FaqCategoryRepository $repository
     * @return void
     */
    protected $appVersionRepository;
  

    public function __construct(GuestBannersRepository $repository, AppVersionRepository $appVersionRepository ,GuestBannerConfigRepository $guestBannerConfigRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->appVersionRepository = $appVersionRepository;
        $this->guestBannerConfigRepository = $guestBannerConfigRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return view as response
     */
    public function index(GuestBanners $guestbanner)
    {
        $selectedAppVersionArray = [];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $screenList = $this->guestBannerConfigRepository->listGuestBannerConfigData()->toArray();
        //['Dashboard' => 'Dashboard','InternationalRoaming' => 'International Roaming', 'IdeaTuesday' => 'IdeaTuesday', 'ThankYou' => 'Thank You', 'DashboardWidget' => 'Dashboard Widget', 'DashboardUpperBanner' => 'Dashboard Upper Banner', 'RechargePage' => 'Recharge Page', 'DashboardCenterBanner' => 'Dashboard Center Banner', 'ActivePacksServices' => 'Active Packs & Services', 'Downtime' => 'Downtime', 'FullPageBanner' => 'FullPageBanner', 'ExclusiveOffersBanner' => 'ExclusiveOffersBanner', 'CashbackOffersBanner' => 'CashbackOffersBanner', 'ViRiseBanner' => 'ViRiseBanner','Coupon'=>'Coupon'];
        return view('admin::guestbanner.index', compact('rankList','appVersionList','osList','screenList','selectedAppVersionArray','guestbanner'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData(Request $request)
    {
        $guestbanner = $this->repository->data();
        //echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $guestbanner = $guestbanner->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($guestbanner)

                ->addColumn('banner_check', function ($guestbanner) {
                    $status = '<input class="form-check-input" name="multi_chk[]"  type="checkbox" value="'.$guestbanner->id.'" id="chk_'.$guestbanner->id.'" >';
                    return $status;
                })

                ->addColumn('status', function ($guestbanner) {
                    $status = ($guestbanner->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })

                ->addColumn('thumbnail_image', function ($guestbanner) {
                    $fileURL = config('app.assets_url').$guestbanner->banner_image; 
                    $path_info = pathinfo($guestbanner->banner_image);
                    return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($guestbanner->id, $guestbanner->banner_image) . '</div>';
                      
                })
                ->addColumn('updated_at', function ($guestbanner) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($guestbanner->updated_at));
                    return $updated_at;
                })
                ->addColumn('app_version', function ($guestbanner) {
                    $versionList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    //$circle = $circleList[$banner->circle];
                    
                    $versions = 'NA';
                    if($guestbanner->app_version != NULL){
                        $versionsArr = explode(',',$guestbanner->app_version);
                        $versions = implode(", ",$versionsArr);
                    }
                    return $versions;
                })

                ->addColumn('action', function ($guestbanner) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($guestbanner->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $guestbanner->id . '" data-action="edit" data-id="' . $guestbanner->id . '" id="' . $guestbanner->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($guestbanner->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $guestbanner->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })

                ->filter(function ($instance) use ($request) {
                    if ($request->has('banner_title')) {
                       $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                           return Str::contains(strtolower($row['banner_title']), strtolower($request->get('banner_title'))) ? true : false;
                       });
                    }
                    if ($request->has('app_version') && $request->get('app_version') != 'All Versions') {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['app_version']), strtolower($request->get('app_version'))) or Str::contains(strtolower($row['app_version']), strtolower('All Versions')) ? true : false;
                        });
                    }
                    if ($request->has('banner_screen')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(strtolower($row['banner_screen']), strtolower($request->get('banner_screen'))) ? true : false;
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
     * Display a form to create new faq category.
     *
     * @return view as response
     */
    public function create()
    {
        return view('admin::guestbanner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(GuestBannersCreateRequest $request)
    {
        $response = $this->repository->create($request->all());
        return response()->json($response);
    }

    

    /**
     * Show the form for editing the specified faq category.
     *
     * @param  Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function edit(GuestBanners $guestbanner)
    {
        
        //
        $response['success'] = true;
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $appVersionList = $this->appVersionRepository->listAppVersionData()->toArray();
        $osList = ["android" => 'android','ios' => 'ios', 'Both' => 'Both'];
        $screenList = $this->guestBannerConfigRepository->listGuestBannerConfigData()->toArray();
        //['Dashboard' => 'Dashboard','InternationalRoaming' => 'International Roaming', 'IdeaTuesday' => 'IdeaTuesday', 'ThankYou' => 'Thank You', 'DashboardWidget' => 'Dashboard Widget', 'DashboardUpperBanner' => 'Dashboard Upper Banner', 'RechargePage' => 'Recharge Page', 'DashboardCenterBanner' => 'Dashboard Center Banner', 'ActivePacksServices' => 'Active Packs & Services', 'Downtime' => 'Downtime', 'FullPageBanner' => 'FullPageBanner', 'ExclusiveOffersBanner' => 'ExclusiveOffersBanner', 'CashbackOffersBanner' => 'CashbackOffersBanner', 'ViRiseBanner' => 'ViRiseBanner','Coupon'=>'Coupon'];
        $selectedAppVersionArray = [];
        if($guestbanner->app_version != NULL){
            $selectedAppVersionArray = explode(',', $guestbanner->app_version);
        }
        $response['form'] = view('admin::guestbanner.edit', compact('rankList', 'appVersionList', 'osList','screenList','selectedAppVersionArray','guestbanner'))->render();
       // echo "<pre>";print_r($guestbanner);die;
        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(GuestBannersUpdateRequest $request, GuestBanners $guestbanner)
    {

        $response = $this->repository->update($request->all(), $guestbanner);
        return response()->json($response);
    }


    public function groupAction(Request $request)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        return $result;
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(GuestBannersDeleteRequest $request, GuestBanners $guestbanner)
    {
        //dd("here" . $faqCategory);
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Guest Banner'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Guest Banner'])];
        }

        return response()->json($response);
    }
}
