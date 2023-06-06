<?php
/**
 * The class for user manage specific actions.
 *
 *
 * @author Gaurav Patel <gauravp@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\UserRepository;
use Modules\Admin\Http\Requests\UserCreateRequest;
use Modules\Admin\Http\Requests\UserUpdateRequest;
use Modules\Admin\Models\User;
use Modules\Admin\Repositories\UserTypeRepository;
use Datatables;
use Modules\Admin\Services\Helper\ImageHelper;
use Illuminate\Support\Str;
use Modules\Admin\Repositories\LinksRepository;
use Modules\Admin\Repositories\LinkCategoryRepository;
use Form;
use Auth;
use Modules\Admin\Services\Helper\ConfigConstantHelper;

class UserController extends Controller
{

    /**
     * The UserRepository instance.
     *
     * @var Modules\Admin\Repositories\UserRepository
     */
    protected $repository;

    /**
     * The UserTypeRepository instance.
     *
     * @var Modules\Admin\Repositories\UserTypeRepository
     */
    protected $userTypeRepository;

    /**
     * The LinksRepository instance.
     *
     * @var Modules\Admin\Repositories\LinksRepository
     */
    protected $linksRepository;

    /**
     * The LinkCategoryRepository instance.
     *
     * @var Modules\Admin\Repositories\LinkCategoryRepository
     */
    protected $linkCategoryRepository;

    /**
     * Create a new UserController instance.
     *
     * @param  Modules\Admin\Repositories\UserRepository $repository
     * @param  Modules\Admin\Repositories\UserTypeRepository $userTypeRepository
     * @param  Modules\Admin\Repositories\LinksRepository $linksRepository
     * @param  Modules\Admin\Repositories\LinkCategoryRepository $linkCategoryRepository
     * @return void
     */
    public function __construct(
    UserRepository $repository, UserTypeRepository $userTypeRepository, LinksRepository $linksRepository, LinkCategoryRepository $linkCategoryRepository)
    {
        parent::__construct();
        $this->middleware('acl');
        $this->repository = $repository;
        $this->userTypeRepository = $userTypeRepository;
        $this->linksRepository = $linksRepository;
        $this->linkCategoryRepository = $linkCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Datatables $datatables
     * @return Response
     */
    public function getData(Request $request)
    {
        $users = $this->repository->data();

        return Datatables::of($users)
                ->addColumn('ids', function ($user) {
                    $checkbox = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($user->created_by == Auth::user()->id))) {
                        $checkbox = '<input type="checkbox" name="ids[]" value="' . $user->id . '">';
                    } else if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($user->created_by == Auth::user()->id))) {
                        $checkbox = '<input type="checkbox" name="ids[]" value="' . $user->id . '">';
                    }
                    return $checkbox;
                })
                ->addColumn('avatar', function ($user) {
                    return '<div class="user-listing-img">' . ImageHelper::getUserAvatar($user->id, $user->avatar) . '</div>';
                })
                ->addColumn('username', function ($user) {
                    $userType = (!empty($user->UserType->name)) ? $user->UserType->name : '';
                    return $user->username . ' (<strong>' . $userType . '</strong>) <br/> Id (' . $user->id . ')';
                })
                ->addColumn('email', function ($user) {
                    $gender = ($user->gender == 1) ? 'Male' : 'Female';
                    return $user->first_name . ' ' . $user->last_name . ' (' . $gender . ') <br/>' . $user->email . ' </br>' . $user->contact;
                })
                ->addColumn('links', function ($user) {
                    return Form::select('user_links', $this->repository->getUserSelectLinks($user->id), ['class' => 'select2me form-control']);
                })
                ->addColumn('created_at', function ($user) {
                    $dateTimeFormat = ConfigConstantHelper::getValue('C_DATEFORMAT');
                    $created_at = date($dateTimeFormat, strtotime($user->created_at));
                    return $created_at;
                })
                ->addColumn('status', function ($user) {
                    return ($user->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                })
                ->addColumn('action', function ($user) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($user->created_by == Auth::user()->id))) {

                        $actionList .= '<a href="javascript:;" data-action="edit" data-id="' . $user->id . '" class="btn btn-xs default yellow-gold margin-bottom-5 edit-form-link edit" title="' . trans('admin::messages.edit') . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($user->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete" data-id="' . $user->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })
                ->filter(function ($instance) use ($request) {

                    //to display own records
                    if (Auth::user()->hasOwnView) {
                        $instance->collection = $instance->collection->filter(function ($row) {
                            return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
                        });
                    }
                    if ($request->has('username')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return (Str::contains($row['username'], $request->get('username')) || Str::equals($row['id'], $request->get('username'))) ? true : false;
                        });
                    }
                    if ($request->has('email')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return (Str::contains($row['email'], $request->get('email')) || Str::contains($row['first_name'], $request->get('email')) || Str::contains($row['last_name'], $request->get('email')) || Str::contains($row['contact'], $request->get('email'))) ? true : false;
                        });
                    }
                    if ($request->has('status')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::equals($row['status'], $request->get('status')) ? true : false;
                        });
                    }
                })
                ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $bulkAction = $this->repository->getAdminBulkActionSelect();
        return view('admin::users.index', compact('bulkAction'));
    }

    /**
     * Hadle Ajax Group Action
     *
     * @param  Illuminate\Http\Request $request
     * @return Response
     */
    public function groupAction(Request $request)
    {
        $response = $this->repository->groupAction($request->all());
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     * @param  Modules\Admin\Repositories\UserTypeRepository $userTypeRepository
     * @return Response
     */
    public function create()
    {
        $userType = $this->userTypeRepository->listUserTypeData()->toArray();
        return view('admin::users.create', compact('userType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest $request
     * @return Response
     */
    public function store(UserCreateRequest $request)
    {
        $response = $this->repository->create($request->all());
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Modules\Admin\Models\User
     * @return Response
     */
    public function edit(User $user)
    {
        $links = $this->linkCategoryRepository->getLinks($user->user_type_id);
        $userLinks = $this->repository->listUserLinksWithColumns($user->id);
        $userType = $this->userTypeRepository->listUserTypeData()->toArray();
        $viewRecords = ConfigConstantHelper::getValue('ONLY_VIEW_RECORDS');
        return view('admin::users.edit', compact('user', 'userType', 'links', 'userLinks', 'viewRecords'));
    }

    /**
     * Show the form for user links based on user type selection
     *
     * @param  Request $request
     * @return Response
     */
    public function getUserLinks(Request $request)
    {
        $userTypeId = (!empty($request['user_type'])) ? $request['user_type'] : '';
        $userId = (!empty($request['user_id'])) ? $request['user_id'] : '';
        $links = $this->linkCategoryRepository->getLinks($userTypeId);
        $userLinks = $this->repository->listUserLinksWithColumns($userId);
        $viewRecords = ConfigConstantHelper::getValue('ONLY_VIEW_RECORDS');
        $response['success'] = true;
        $response['form'] = view('admin::users.user-links', compact('links', 'userLinks', 'viewRecords'))->render();

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\UserUpdateRequest $request
     * @param  Modules\Admin\Models\User
     * @return Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $response = $this->repository->update($request->all(), $user);
        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function trashed()
    {
        $bulkAction = $this->repository->getTrashBulkActionSelect();
        return view('admin::users.trashed', compact('bulkAction'));
    }

    /**
     * Display a listing of the trashed resources.
     *
     * @param  Request $request
     * @return Response
     */
    public function getTrashedData(Request $request)
    {
        $users = $this->repository->trashedData();

        return Datatables::of($users)
                ->addColumn('ids', function ($user) {
                    return '<input type="checkbox" name="ids[]" value="' . $user->id . '">';
                })
                ->addColumn('avatar', function ($user) {
                    return '<div class="user-listing-img">' . ImageHelper::getUserAvatar($user->id, $user->avatar) . '</div>';
                })
                ->addColumn('username', function ($user) {
                    $userType = (!empty($user->UserType->name)) ? $user->UserType->name : '';
                    return $user->username . ' (<strong>' . $userType . '</strong>) <br/> Id (' . $user->id . ')';
                })
                ->addColumn('email', function ($user) {
                    $gender = ($user->gender == 1) ? 'Male' : 'Female';
                    return $user->first_name . ' ' . $user->last_name . ' (' . $gender . ') <br/>' . $user->email . ' </br>' . $user->contact;
                })
                ->addColumn('links', function ($user) {
                    return Form::select('user_links', $this->repository->getUserSelectLinks($user->id), ['class' => 'select2me form-control']);
                })
                ->addColumn('status', function ($user) {
                    return ($user->status == 1) ? '<span class="label label-sm label-success">' . trans('admin::messages.active') . '</span>' : '<span class="label label-sm label-danger">' . trans('admin::messages.inactive') . '</span>';
                })
                ->addColumn('action', function ($user) {

                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($user->created_by == Auth::user()->id))) {
                        $actionList = '<a href="javascript:;" data-message="' . trans('admin::messages.delete-confirm') . '" data-action="delete-hard" data-id="' . $user->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.delete-hard') . '"><i class="fa fa-trash-o"></i></a>';
                    }
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($user->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-message="' . trans('admin::messages.restore-confirm') . '" data-action="restore" data-id="' . $user->id . '" class="btn btn-xs default red-thunderbird margin-bottom-5 delete" title="' . trans('admin::messages.restore') . '"><i class="fa fa-undo"></i></a>';
                    }

                    return $actionList;
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->has('username')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return (Str::contains($row['username'], $request->get('username')) || Str::equals($row['id'], $request->get('username'))) ? true : false;
                        });
                    }
                    if ($request->has('email')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return (Str::contains($row['email'], $request->get('email')) || Str::contains($row['first_name'], $request->get('email')) || Str::contains($row['last_name'], $request->get('email')) || Str::contains($row['contact'], $request->get('email'))) ? true : false;
                        });
                    }
                    if ($request->has('status')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::equals($row['status'], $request->get('status')) ? true : false;
                        });
                    }
                })
                ->make(true);
    }

    /**
     * check field Avalability
     *
     * @param  Request $request
     * @return Response
     */
    public function checkAvalability(Request $request)
    {
        $response = [];
        $result = $this->repository->checkField($request->all());
        if ($result) {
            $response = ['status' => 'fail', 'message' => trans('admin::controller/user.username-check-error', ['name' => $request['value']])];
        } else {
            $response = ['status' => 'success', 'message' => trans('admin::controller/user.username-check-success', ['name' => $request['value']])];
        }

        return response()->json($response);
    }
}
