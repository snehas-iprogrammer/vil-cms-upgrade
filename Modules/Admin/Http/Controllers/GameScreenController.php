<?php
/**
 * The class for managing GameScreen actions.
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
use Modules\Admin\Models\GameScreen;
use Modules\Admin\Repositories\GameScreenRepository;
use Modules\Admin\Http\Requests\GameScreenCreateRequest;
use Modules\Admin\Http\Requests\GameScreenUpdateRequest;
use Modules\Admin\Http\Requests\GameScreenDeleteRequest;

class GameScreenController extends Controller
{

    /**
     * The GameScreenRepository instance.
     *
     * @var Modules\Admin\Repositories\GameScreenRepository
     */
    protected $repository;
    /**
     * Create a new FaqCategoryController instance.
     *
     * @param  Modules\Admin\Repositories\GameScreenRepository $repository
     * @return void
     */
    public function __construct(GameScreenRepository $repository)
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
    public function index(GameScreen $gamescreen)
    {
        $typeList = ['vi'=>'Vi','guest'=>'Guest'];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        return view('admin::gamescreen.index',compact('rankList','typeList'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return 
     */
    public function getData()
    {
        $gamescreen = $this->repository->data();
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $gamescreen = $gamescreen->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($gamescreen)
                ->addColumn('status', function ($gamescreen) {
                    $status = ($gamescreen->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                    return $status;
                })

                ->addColumn('action', function ($gamescreen) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($gamescreen->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $gamescreen->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $gamescreen->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($gamescreen->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $gamescreen->id . ' created_by = ' . $gamescreen->created_by . ' ><i class="fa fa-trash-o"></i></a>';
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
        return view('admin::gamescreen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\GameScreenCreateRequest $request
     * @return json encoded Response
     */
    public function store(GameScreenCreateRequest $request)
    {

        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified faq category.
     *
     * @param  Modules\Admin\Models\GameScreen $gamescreen
     * @return json encoded Response
     */
    public function edit(GameScreen $gamescreen)
    {
        $typeList = ['vi'=>'Vi','guest'=>'Guest'];
        $rankList = ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'];
        $response['success'] = true;
        $response['form'] = view('admin::gamescreen.edit', compact('gamescreen','rankList','typeList'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\GameScreenUpdateRequest $request, Modules\Admin\Models\FaqCategory $faqCategory
     * @return json encoded Response
     */
    public function update(GameScreenUpdateRequest $request, GameScreen $gamescreen)
    {
        $response = $this->repository->update($request->all(), $gamescreen);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\GameScreenDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(GameScreenDeleteRequest $request, GameScreen $gamescreen)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => 'GameScreen'])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => 'GameScreen'])];
        }

        return response()->json($response);
    }
}
