<?php
namespace Modules\Admin\Http\Controllers;

class DashboardController extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin::index');
    }
}
