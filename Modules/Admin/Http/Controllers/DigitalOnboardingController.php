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
use Modules\Admin\Models\DigitalOnboarding;
use Modules\Admin\Repositories\DigitalOnboardingRepository;
use Modules\Admin\Http\Requests\DigitalOnboardingCreateRequest;
use Modules\Admin\Http\Requests\DigitalOnboardingUpdateRequest;
use Modules\Admin\Http\Requests\DigitalOnboardingDeleteRequest;

class DigitalOnboardingController extends Controller
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
    public function __construct(DigitalOnboardingRepository $repository)
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
    public function index()
    {
        return view('admin::digital-onboarding.index');
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData()
    {
        $digitalOnboarding = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $digitalOnboarding = $digitalOnboarding->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($digitalOnboarding)
                ->addColumn('status', function ($digitalOnboarding) {
                    $status = ($digitalOnboarding->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })
                ->addColumn('action', function ($digitalOnboarding) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($digitalOnboarding->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $digitalOnboarding->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $digitalOnboarding->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($digitalOnboarding->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $digitalOnboarding->id . ' created_by = ' . $digitalOnboarding->created_by . ' ><i class="fa fa-trash-o"></i></a>';
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
        return view('admin::digital-onboarding.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request
     * @return json encoded Response
     */
    public function store(DigitalOnboardingCreateRequest $request)
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
    public function edit(DigitalOnboarding $digitalOnboarding)
    {
        $response['success'] = true;
        $response['form'] = view('admin::digital-onboarding.edit', compact('digitalOnboarding'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryCreateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(DigitalOnboardingUpdateRequest $request, DigitalOnboarding $digitalOnboarding)
    {
        $response = $this->repository->update($request->all(), $digitalOnboarding);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\FaqCategoryDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(DigitalOnboardingDeleteRequest $request, DigitalOnboarding $digitalOnboarding)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'Digital Onboarding'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'Digital Onboarding'])];
        }

        return response()->json($response);
    }
}
