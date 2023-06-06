<?php
/**
 * The class for managing MasterQuickLink specific actions.
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
use Modules\Admin\Models\MasterQuickLink;
use Modules\Admin\Repositories\MasterQuickLinkRepository;
use Modules\Admin\Http\Requests\MasterQuickLinkCreateRequest;
use Modules\Admin\Http\Requests\MasterQuickLinkUpdateRequest;
use Modules\Admin\Http\Requests\MasterQuickLinkDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class MasterQuickLinkController extends Controller
{

    /**
     * The MasterQuickLinkRepository instance.
     *
     * @var Modules\Admin\Repositories\MasterQuickLinkRepository
     */
    protected $repository;
  
    /**
     * Create a new MasterQuickLinkController instance.
     *
     * @param  Modules\Admin\Repositories\MasterQuickLinkRepository $repository
     * @return void
     */
    public function __construct(MasterQuickLinkRepository $repository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(MasterQuickLink $masterquicklink)
    {
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        return view('admin::MasterQuickLink.index', compact('masterquicklink','linkTypeList'));
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
            $response['message'] = trans('admin::messages.deleted', ['name' => 'MasterQuickLink']);
        } else {
            $response['status'] = 'fail';
            $response['message'] = trans('admin::messages.notDeleted', ['name' => 'MasterQuickLink']);
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
        $masterquicklinks = $this->repository->data();

        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $masterquicklinks = $masterquicklinks->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($masterquicklinks)
                ->addColumn('status', function ($masterquicklinks) {
                    $status = ($masterquicklinks->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('name', function ($masterquicklink) {
                    $title = '';
                    if($masterquicklink->name != NULL){
                        $title = $masterquicklink->name;
                    }
                    return $title;
                })
                ->addColumn('title', function ($masterquicklink) {
                    $title = '';
                    if($masterquicklink->title != NULL){
                        $title = $masterquicklink->title;
                    }
                    return $title;
                })
                ->addColumn('TealiumEvents', function ($masterquicklink) {
                    $TealiumEvents = '';
                    if($masterquicklink->TealiumEvents != NULL){
                        $TealiumEvents = $masterquicklink->TealiumEvents;
                    }
                    return $TealiumEvents;
                })

                ->addColumn('cardType', function ($masterquicklink) {
                    $cardType = '';
                    if($masterquicklink->cardType != NULL){
                        $cardType = $masterquicklink->cardType;
                    }
                    return $cardType;
                })
                ->addColumn('tag', function ($masterquicklink) {
                    $tag = '';
                    if($masterquicklink->tag != NULL){
                        $tag = $masterquicklink->tag;
                    }
                    return $tag;
                })
                
                ->addColumn('sequenceNumber', function ($masterquicklink) {
                    $sequenceNumber = '';
                    if($masterquicklink->sequenceNumber != NULL){
                        $sequenceNumber = $masterquicklink->sequenceNumber;
                    }
                    return $sequenceNumber;
                })

                ->addColumn('thumbnail_image', function ($masterquicklink) {
                    $fileURL = config('app.assets_url').$masterquicklink->imageUrl; 
                    $path_info = pathinfo($masterquicklink->imageUrl);
                    if($path_info['extension'] == 'json'){
                        return 'JSON File<br> <b>'.$fileURL.'</b>';
                    }else{
                        return '<div class="testimonial-listing-img">' . ImageHelper::getBannerImagePath($masterquicklink->id, $masterquicklink->imageUrl) . '</div>';
                    }   
                })
                ->addColumn('updated_at', function ($masterquicklink) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($masterquicklink->updated_at));
                    return $updated_at;
                })
                
               
                ->addColumn('internalLink', function ($masterquicklink) {
                    $actionList = '-';
                    if($masterquicklink->internalLink != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$masterquicklink->internalLink;
                    }
                    
                    if($masterquicklink->externalLink != NULL){
                        $actionList = '<b>External Link</b> </br> '.$masterquicklink->externalLink;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($masterquicklink) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit))) {
                        $actionList = '<a href="javascript:;" id="' . $masterquicklink->id . '" data-action="edit" data-id="' . $masterquicklink->id . '" id="' . $masterquicklink->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $masterquicklink->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })

                ->make(true);
    }

    /**
     * Display a form to create new MasterQuickLink.
     *
     * @return view as response
     */
    public function create()
    {

        return view('admin::MasterQuickLinks.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\MasterQuickLinkCreateRequest $request
     * @return json encoded Response
     */
    public function store(MasterQuickLinkCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(MasterQuickLink $masterquicklink)
    {
        $response['success'] = true;
        $linkTypeList = ['1' => 'Internal Link','2' => 'External Link'];
        $response['form'] = view('admin::MasterQuickLink.edit', compact('masterquicklink','linkTypeList'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\MasterQuickLinkUpdateRequest $request, Modules\Admin\Models\MasterQuickLink $masterquicklink
     * @return json encoded Response
     */
    public function update(MasterQuickLinkUpdateRequest $request, MasterQuickLink $masterquicklink)
    {
        $response = $this->repository->update($request->all(), $masterquicklink);

        return response()->json($response);
    }
    
}
