<?php
/**
 * The class for handling validation requests from TestimonialsController::deleteAction()
 *
 *
 * @author Sachin S. <sachins@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */

namespace Modules\Admin\Http\Controllers;

use Auth;
use Datatables;
use Modules\Admin\Services\Helper\ImageHelper;
use Illuminate\Support\Str;
use Modules\Admin\Models\Gallery;
use Modules\Admin\Repositories\GalleryRepository;
use Modules\Admin\Http\Requests\GalleryCreateRequest;
use Modules\Admin\Http\Requests\GalleryUpdateRequest;
use Modules\Admin\Http\Requests\GalleryDeleteRequest;
use Illuminate\Http\Request;

class GalleryController extends Controller
{

    /**
     * The TestimonialsRepository instance.
     *
     * @var Modules\Admin\Repositories\TestimonialsRepository
     */
    protected $repository;

    /**
     * Create a new TestimonialsController instance.
     *
     * @param  Modules\Admin\Repositories\TestimonialsRepository $repository
     * @return void
     */
    public function __construct(GalleryRepository $repository)
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
    public function index(Gallery $gallery)
    {
        return view('admin::gallery.index', compact('gallery'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return datatable
     */
    public function getData(Gallery $gallery, Request $request)
    {
        $gallery = $this->repository->data();
        //echo '<pre>'; print_r($gallery); die;
        
        //filter to display own records
        if (Auth::user()->hasOwnView && (empty(Auth::user()->hasEdit) || empty(Auth::user()->hasDelete))) {
            $gallery = $gallery->filter(function ($row) {
                return (Str::equals($row['created_by'], Auth::user()->id)) ? true : false;
            });
        }

        return Datatables::of($gallery)
                ->addColumn('thumbnail_image', function ($gallery) {
                    return '<div class="testimonial-listing-img">' . ImageHelper::getGalleryImg($gallery->id, $gallery->thumbnail_image) . '</div>';
                })
                
                ->addColumn('image', function ($gallery) {
                    return '<div class="testimonial-listing-img">' . ImageHelper::getGalleryImg($gallery->id, $gallery->image) . '</div>';
                })
                
                ->addColumn('status', function ($gallery) {
                    if($gallery->status == 1){
                        return '<span class="label label-sm label-success">Active<span>';
                    }else{
                        return '<span class="label label-sm label-danger">Inactive<span>';
                    }
                })
                ->addColumn('action', function ($gallery) {
                    $actionList = '';
                    if (!empty(Auth::user()->hasEdit) || (!empty(Auth::user()->hasOwnEdit) && ($gallery->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" data-action="edit" title="Edit" data-id="' . $gallery->id . '" class="btn btn-xs default margin-bottom-5 yellow-gold edit-form-link" title="Edit" id="' . $gallery->id . '"><i class="fa fa-pencil"></i></a>';
                    }
                    if (!empty(Auth::user()->hasDelete) || (!empty(Auth::user()->hasOwnDelete) && ($gallery->created_by == Auth::user()->id))) {
                        $actionList .= '<a href="javascript:;" class="btn btn-xs default delete red-thunderbird margin-bottom-5" title="Delete" id =' . $gallery->id . ' created_by = ' . $gallery->created_by . ' ><i class="fa fa-trash-o"></i></a>';
                    }
                    return $actionList;
                })
                ->filter(function ($instance) use ($request) {
                    if (trim($request->has('title'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return (Str::contains($row['title'], trim($request->get('title'))) || Str::equals($row['title'], trim($request->get('title')))) ? true : false;
                        });
                    }
                    
                    if (trim($request->has('ordera'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            //return (Str::contains($row['order'], trim($request->get('order'))) || Str::equals($row['order'], trim($request->get('order')))) ? true : false;
                            return Str::equals((string) $row['order'], $request->get('ordera')) ? true : false;
                        });
                    }
                    
                    if ($request->has('status')) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::equals((string) $row['status'], $request->get('status')) ? true : false;
                        });
                    }
                })
                ->make(true);
    }

    /**
     * Display a form to create new testimonials.
     *
     * @return view as response
     */
    public function create(Gallery $gallery)
    {
        return view('admin::gallery.create', compact('gallery'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\TestimonialsCreateRequest $request
     * @return json encoded Response
     */
    public function store(GalleryCreateRequest $request)
    {
        $response = $this->repository->create($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified testimonials.
     *
     * @param  Modules\Admin\Models\Testimonials $testimonials
     * @return json encoded Response
     */
    public function edit(Gallery $gallery)
    {
        $response['success'] = true;
        $response['form'] = view('admin::gallery.edit', compact('gallery'))->render();

        return response()->json($response);
    }

    /**
     * Store an updated resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\TestimonialsCreateRequest $request, Modules\Admin\Models\Testimonials $testimonials
     * @return json encoded Response
     */
    public function update(GalleryUpdateRequest $request, Gallery $gallery)
    {
        $response = $this->repository->update($request->all(), $gallery);

        return response()->json($response);
    }

    /**
     * Delete resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\TestimonialsDeleteRequest $request
     * @return json encoded Response
     */
    public function destroy(GalleryDeleteRequest $request, Gallery $gallery)
    {
        $response = [];
        $result = $this->repository->deleteAction($request->all());
        if ($result) {
            $response = ['status' => 'success', 'message' => trans('admin::messages.deleted', ['name' => trans('Gallery')])];
        } else {
            $response = ['status' => 'fail', 'message' => trans('admin::messages.not-deleted', ['name' => trans('Gallery')])];
        }

        return response()->json($response);
    }
}