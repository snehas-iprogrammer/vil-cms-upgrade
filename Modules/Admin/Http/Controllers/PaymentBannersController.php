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
use Modules\Admin\Models\PaymentBanners;
use Modules\Admin\Repositories\PaymentBannersRepository;
use Modules\Admin\Http\Requests\PaymentBannersCreateRequest;
use Modules\Admin\Http\Requests\PaymentBannersUpdateRequest;
use Modules\Admin\Http\Requests\PaymentBannersDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class PaymentBannersController extends Controller
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
    public function __construct(PaymentBannersRepository $repository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(PaymentBanners $paymentBanners)
    {
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $rank =['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        return view('admin::payment-banners.index', compact('lobList','rank'));
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
        $paymentBanners = $this->repository->data();
        //echo '<pre>'; print_r($banners); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $paymentBanners = $paymentBanners->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($paymentBanners)
                ->addColumn('thumbnail_image', function ($paymentBanners) {
                    return '<div class="testimonial-listing-img">' . ImageHelper::getPaymentBannerImagePath($paymentBanners->id, $paymentBanners->image) . '</div>';
                })
                ->addColumn('created_at', function ($paymentBanners) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $created_at = date($dateTimeFormat, strtotime($paymentBanners->created_at));
                    return $created_at;
                }) 
                ->addColumn('status', function ($banners) {
                    $status = ($banners->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })               
                ->addColumn('action', function ($paymentBanners) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($paymentBanners->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $paymentBanners->id . '" data-action="edit" data-id="' . $paymentBanners->id . '" id="' . $paymentBanners->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($paymentBanners->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $paymentBanners->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
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

        return view('admin::payment-banners.create', compact(''));
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
    public function edit(PaymentBanners $paymentBanners)
    {
        $lobList = ['Prepaid' => 'Prepaid', 'Postpaid' => 'Postpaid', 'Both' => 'Both'];
        $rank =['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $response['success'] = true;
        $response['form'] = view('admin::payment-banners.edit', compact('paymentBanners', 'lobList','rank'))->render();
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(Request $request, PaymentBanners $paymentBanners)
    {
        $response = $this->repository->update($request->all(), $paymentBanners);

        return response()->json($response);
    }
    
}
