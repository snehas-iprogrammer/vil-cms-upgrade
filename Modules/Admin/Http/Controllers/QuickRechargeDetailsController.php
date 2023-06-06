<?php
/**
 * The class for managing faq categories specific actions.
 * 
 * 
 * @author Sachin Sonune <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Support\Str;
use Modules\Admin\Models\QuickRechargeDetails;
use Modules\Admin\Repositories\QuickRechargeDetailsRepository;
use Modules\Admin\Http\Requests\QuickRechargeDetailsCreateRequest;
use Modules\Admin\Http\Requests\QuickRechargeDetailsUpdateRequest;
use Modules\Admin\Http\Requests\QuickRechargeDetailsDeleteRequest;

class QuickRechargeDetailsController extends Controller
{

    /**
     * The FaqCategoryRepository instance.
     *
     * @var Modules\Admin\Repositories\FaqCategoryRepository
     */
    protected $repository;

    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\FaqCategoryRepository $repository
     * @return void
     */
    public function __construct(QuickRechargeDetailsRepository $repository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return view as response
     */
    public function index(QuickRechargeDetails $quickRechargeDetails)
    {
        $selectedCirclesArray = [];
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        return view('admin::quick-recharge-details.index', compact('quickRechargeDetails','circleList','selectedCirclesArray'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $quickRechargeDetails = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $quickRechargeDetails = $quickRechargeDetails->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($quickRechargeDetails)
                ->addColumn('status', function ($quickRechargeDetails) {
                    $status = ($quickRechargeDetails->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('circle', function ($quickRechargeDetails) {
                    $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
                    $circle = 'NA';
                    if($quickRechargeDetails->circle != NULL){
                        $circleArr = explode(',',$quickRechargeDetails->circle);
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
                ->addColumn('action', function ($quickRechargeDetails) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($quickRechargeDetails->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $quickRechargeDetails->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $quickRechargeDetails->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($quickRechargeDetails->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $quickRechargeDetails->id . ' created_by = ' . $quickRechargeDetails->created_by . ' ><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
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
        return view('admin::quick-recharge-details.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(QuickRechargeDetailsCreateRequest $request)
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
    public function edit(QuickRechargeDetails $quickRechargeDetails)
    {
        $circleList = ["0000" => 'All Circle',"0001" => 'Andhra Pradesh','0002' => 'Chennai','0003' => 'Delhi','0004' => 'Uttar Pradesh East','0005' => 'Gujarat','0006' => 'Haryana','0007' => 'Karnataka','0008' => 'Kolkata','0009' => 'Mumbai','0010' => 'Rajastan','0011' => 'West Bengal','0012' => 'Punjab','0013' => 'Uttar Pradesh West','0014' => 'Maharashtra','0015' => 'Tamil Nadu','0016' => 'Kerala','0017' => 'Orissa','0018' => 'Assam','0019' => 'North East','0020' => 'Bihar','0021' => 'Madhya Pradesh','0022' => 'Himachal Pradesh','0023' => 'Jammu And Kashmir'];
        $selectedCirclesArray = [];
        if($quickRechargeDetails->circle != NULL){
            $selectedCirclesArray = explode(',', $quickRechargeDetails->circle);
        }
        
        $response['success'] = true;
        $response['form'] = view('admin::quick-recharge-details.edit', compact('quickRechargeDetails', 'circleList', 'selectedCirclesArray'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(QuickRechargeDetailsUpdateRequest $request, QuickRechargeDetails $quickRechargeDetails)
    {
        $response = $this->repository->update($request->all(), $quickRechargeDetails);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(QuickRechargeDetailsDeleteRequest $request, QuickRechargeDetails $quickRechargeDetails)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Quick Recharge Details'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Quick Recharge Details'])];
        }

        return response()->json($response);
    }
}
