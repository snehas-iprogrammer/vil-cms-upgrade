<?php
/**
 * The class for managing Ideatuesday specific actions.
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
use Modules\Admin\Models\Ideatuesday;
use Modules\Admin\Repositories\IdeatuesdayRepository;
use Modules\Admin\Http\Requests\IdeatuesdayCreateRequest;

class IdeatuesdayController extends Controller
{

    /**
     * The IdeatuesdayRepository instance.
     *
     * @var Modules\Admin\Repositories\IdeatuesdayRepository
     */
    protected $repository;

    /**
     * Create a new IdeatuesdayController instance.
     *
     * @param  Modules\Admin\Repositories\IdeatuesdayRepository $repository
     * @return void
     */
    public function __construct(IdeatuesdayRepository $repository)
    {
        parent::__construct();
       // $this->middleware('acl');
        $this->repository = $repository;
    }

    /**
     * List all the data
     * @return response
     */
    public function index(Ideatuesday $banner)
    {
        $imagesList = ['IdeaTuesday' => 'Idea Tuesday', 'VodafoneTuesday' => 'Vodafone Tuesday', 'Redx' => 'Redx', 'Brandx' => 'Brandx','FAQExcelFiles' => 'FAQ Excel Files'];
        return view('admin::ideatuesday.index', compact('imagesList'));
    }

    /**
     * Display a form to create new banner.
     *
     * @return view as response
     */
    public function create()
    {
        $imagesList = ['IdeaTuesday' => 'Idea Tuesday', 'VodafoneTuesday' => 'Vodafone Tuesday', 'Redx' => 'Redx', 'Brandx' => 'Brandx','FAQExcelFiles' => 'FAQ Excel Files'];
        return view('admin::ideatuesday.create', compact('imagesList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\BannerCreateRequest $request
     * @return json encoded Response
     */
    public function store(Request $request)
    {   
        //echo '<pre>'; print_r($request->all()); die;
        $response = $this->repository->create($request->all());
        return response()->json($response);
    }
    
}
