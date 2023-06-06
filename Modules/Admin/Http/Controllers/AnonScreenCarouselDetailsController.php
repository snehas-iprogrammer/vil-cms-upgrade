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
use Modules\Admin\Models\AnonScreenCarouselDetails;
use Modules\Admin\Repositories\AnonScreenCarouselDetailsRepository;
use Modules\Admin\Http\Requests\AnonScreenCarouselDetailsCreateRequest;
use Modules\Admin\Http\Requests\AnonScreenCarouselDetailsUpdateRequest;
use Modules\Admin\Http\Requests\AnonScreenCarouselDetailsDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class AnonScreenCarouselDetailsController extends Controller
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
    public function __construct(AnonScreenCarouselDetailsRepository $repository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(AnonScreenCarouselDetails $anonScreenCarouselDetails)
    {
        $screenList = $this->repository->listScreenData()->toArray();
        $mediaTypeList = ["Image" => 'Image','Gif' => 'Gif', 'Video' => 'Video', 'Lottie' => 'Lottie'];
        $shapeList = ["Rectangle" => 'Rectangle','Circle' => 'Circle'];
        return view('admin::anon-screen-carousel-details.index', compact('screenList', 'mediaTypeList', 'shapeList'));
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
            $response['message'] = trans('admin::messages.deleted', ['name' => 'Anon Screen Carousel Details']);
        } else {
            $response['status'] = 'fail';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'Anon Screen Carousel Details']);
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
        $anonScreenCarouselDetails = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $anonScreenCarouselDetails = $anonScreenCarouselDetails->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($anonScreenCarouselDetails)
                ->addColumn('anon_screen_id', function ($anonScreenCarouselDetails) {
                    return (!empty($anonScreenCarouselDetails->Screens->screen_id)) ? $anonScreenCarouselDetails->Screens->screen_id : '';
                })
                ->addColumn('status', function ($anonScreenCarouselDetails) {
                    $status = ($anonScreenCarouselDetails->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('media', function ($anonScreenCarouselDetails) {
                    if(env('AWS_S3_BUCKET') == 'vodafoneideaappimages-dev'){
                        $media = 'https://vodafoneideaappimages-dev.s3.ap-south-1.amazonaws.com' . $anonScreenCarouselDetails->media;
                    }elseif(env('AWS_S3_BUCKET') == 'vodafoneideaappimages'){
                        $media = 'https://vodafoneideaappimages.s3.ap-south-1.amazonaws.com' . $anonScreenCarouselDetails->media;
                    }                   
                    return $media;
                })
                ->addColumn('action', function ($anonScreenCarouselDetails) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($anonScreenCarouselDetails->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $anonScreenCarouselDetails->id . '" data-action="edit" data-id="' . $anonScreenCarouselDetails->id . '" id="' . $anonScreenCarouselDetails->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($anonScreenCarouselDetails->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $anonScreenCarouselDetails->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
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

        return view('admin::anon-screen-carousel-details.create', compact(''));
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
    public function edit(AnonScreenCarouselDetails $anonScreenCarouselDetails)
    {
        $screenList = $this->repository->listScreenData()->toArray();
        $mediaTypeList = ["Image" => 'Image','Gif' => 'Gif', 'Video' => 'Video', 'Lottie' => 'Lottie'];
        $shapeList = ["Rectangle" => 'Rectangle','Circle' => 'Circle'];
        $response['success'] = true;
        $response['form'] = view('admin::anon-screen-carousel-details.edit', compact('screenList', 'mediaTypeList', 'shapeList', 'anonScreenCarouselDetails'))->render();
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerUpdateRequest $request, Modules\Admin\Models\Banner $banner
     * @return json encoded Response
     */
    public function update(Request $request, AnonScreenCarouselDetails $anonScreenCarouselDetails)
    {
        $response = $this->repository->update($request->all(), $anonScreenCarouselDetails);

        return response()->json($response);
    }
    
    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(AnonScreenCarouselDetailsDeleteRequest $request, AnonScreenCarouselDetails $anonScreenCarouselDetails)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Anon Screen Details'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Anon Screen Details'])];
        }

        return response()->json($response);
    }
}
