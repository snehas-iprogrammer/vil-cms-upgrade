<?php
/**
 * The class for managing BannerScreen actions.
 * 
 * 
 * @author Sneha Shete <snehas@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Illuminate\Support\Str;
use Modules\Admin\Models\BannerScreen;
use Modules\Admin\Repositories\BannerScreenRepository;
use Modules\Admin\Http\Requests\BannerScreenCreateRequest;
use Modules\Admin\Http\Requests\BannerScreenUpdateRequest;
use Modules\Admin\Http\Requests\BannerScreenDeleteRequest;

class BannerScreenController extends Controller
{

    /**
     * The BannerScreenRepository instance.
     *
     * @var Modules\Admin\Repositories\BannerScreenRepository
     */
    protected $repository;
    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\BannerScreenRepository $repository
     * @return void
     */
    public function __construct(BannerScreenRepository $repository)
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
    public function index(BannerScreen $Bannerscreen)
    {
        $typeList = ['Dashboard'=>'Dashboard','Thankyou'=>'Thankyou','Other'=>'Other'];
        return view('admin::bannerscreen.index',compact('typeList'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return 
     */
    public function getData()
    {
        $Bannerscreen = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $Bannerscreen = $Bannerscreen->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($Bannerscreen)
                ->addColumn('status', function ($Bannerscreen) {
                    $status = ($Bannerscreen->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('is_timestamp_check', function ($Bannerscreen) {
                    $is_timestamp_check = ($Bannerscreen->is_timestamp_check == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.true') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.false') . '</span>';
                    return $is_timestamp_check;
                })

                ->addColumn('is_component', function ($Bannerscreen) {
                    $is_component = ($Bannerscreen->is_component == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.true') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.false') . '</span>';
                    return $is_component;
                })

                ->addColumn('action', function ($Bannerscreen) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($Bannerscreen->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $Bannerscreen->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $Bannerscreen->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($Bannerscreen->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $Bannerscreen->id . ' created_by = ' . $Bannerscreen->created_by . ' ><i class="fa fa-trash-o"></i></a>';
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
        return view('admin::bannerscreen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerScreenCreateRequest $request
     * @return json encoded Response
     */
    public function store(BannerScreenCreateRequest $request)
    {

        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified faq category.
     *
     * @param  Modules\Admin\Models\BannerScreen $Bannerscreen
     * @return json encoded Response
     */
    public function edit(BannerScreen $bannerscreen)
    {
        $typeList = ['Dashboard'=>'Dashboard','Thankyou'=>'Thankyou','Other'=>'Other'];
        $response['success'] = true;
        $response['form'] = view('admin::bannerscreen.edit', compact('bannerscreen','typeList'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerScreenUpdateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(BannerScreenUpdateRequest $request, BannerScreen $bannerscreen)
    {
    //   /    print_r($Bannerscreen);die;
        $response = $this->repository->update($request->all(), $bannerscreen);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerScreenDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(BannerScreenDeleteRequest $request, BannerScreen $Bannerscreen)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'BannerScreen'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'BannerScreen'])];
        }

        return response()->json($response);
    }
}
