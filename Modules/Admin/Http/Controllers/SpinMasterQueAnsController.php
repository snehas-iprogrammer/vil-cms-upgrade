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
use Modules\Admin\Models\SpinMasterQueAns;
use Modules\Admin\Repositories\SpinMasterQueAnsRepository;
use Modules\Admin\Repositories\AppVersionRepository;
use Modules\Admin\Repositories\TabRepository;
use Modules\Admin\Http\Requests\SpinMasterQueAnsCreateRequest;
use Modules\Admin\Http\Requests\SpinMasterQueAnsUpdateRequest;
use Modules\Admin\Http\Requests\SpinMasterQueAnsDeleteRequest;
use Modules\Admin\Services\Helper\ConfigConstantHelper;
use Modules\Admin\Services\Helper\ImageHelper;

class SpinMasterQueAnsController extends Controller
{

    /**
     * The SpinMasterRepository instance.
     *
     * @var Modules\Admin\Repositories\SpinMasterQueAnsRepository
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
    public function __construct(SpinMasterQueAnsRepository $repository, AppVersionRepository $appVersionRepository, TabRepository $tabRepository)
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
    public function index(SpinMasterQueAns $SpinMasterQueAns)
    {
        return view('admin::spinmasterqueans.index',compact('SpinMasterQueAns'));
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
        $SpinMasterQueAns = $this->repository->data();
      //  echo '<pre>'; print_r($request); die; 
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $SpinMasterQueAns = $SpinMasterQueAns->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($SpinMasterQueAns)

                ->addColumn('checkbox', function ($SpinMasterQueAns) {
                    $status = '<input class="form-check-input" name="multi_chk[]"  type="checkbox" value="'.$SpinMasterQueAns->id.'" id="chk_'.$SpinMasterQueAns->id.'" >';
                    return $status;
                })      

                ->addColumn('status', function ($SpinMasterQueAns) {
                    $status = ($SpinMasterQueAns->status) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                
                ->addColumn('updated_at', function ($SpinMasterQueAns) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $updated_at = date($dateTimeFormat, strtotime($SpinMasterQueAns->updated_at));
                    return $updated_at;
                })

                ->addColumn('link', function ($SpinMasterQueAns) {
                    $actionList = '-';
                    if($SpinMasterQueAns->internal_link != NULL){
                        $actionList = '<b>Internal Link</b> </br> '.$SpinMasterQueAns->internal_link;
                    }
                    
                    if($SpinMasterQueAns->external_link != NULL){
                        $actionList = '<b>External Link</b> </br> '.$SpinMasterQueAns->external_link;
                    }
                    
                    return $actionList;
                })
                ->addColumn('action', function ($SpinMasterQueAns) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($SpinMasterQueAns->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" id="' . $SpinMasterQueAns->id . '" data-action="edit" data-id="' . $SpinMasterQueAns->id . '" id="' . $SpinMasterQueAns->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($SpinMasterQueAns->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $SpinMasterQueAns->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
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
                return view('admin::spinmasterqueans.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\SpinMasterCreateRequest $request
     * @return json encoded Response
     */
    public function store(SpinMasterQueAnsCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified configuration setting.
     *
     
     * @return json encoded Response
     */
    public function edit(SpinMasterQueAns $spinmasterqueans)
    {
        $response['success'] = true;
       
        $response['form'] = view('admin::spinmasterqueans.edit', compact('spinmasterqueans'))->render();

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\SpinMasterUpdateRequest $request, Modules\Admin\Models\SpinMaster $SpinMaster
     * @return json encoded Response
     */
    public function update(SpinMasterQueAnsUpdateRequest $request, SpinMasterQueAns $SpinMasterQueAns)
    {
        $response = $this->repository->update($request->all(), $SpinMasterQueAns);

        return response()->json($response);
    }
    
}
