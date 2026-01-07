<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\{Company, ShortUrl};
use Yajra\DataTables\Facades\DataTables;

class ClientResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return DataTable
     */
    public function index()
    {
        $query = Company::query()
                    // ->where('status', 'active')
                    ->select('id', 'name', 'email', 'status') 
                    ->withCount('users')
                    ->withCount('shortUrls')
                    ->withSum('shortUrls as total_hits', 'hits');
        
        return DataTables::of($query)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Short URLs listing 
     */
    public function urls(Request $request) {
        $query = ShortUrl::query()
                    ->with('company:id,name')
                    ->select('short_urls.*');

        //filter logic
        $query->when($request->filter, function($q, $filter) {
            return match($filter) {
                'today' => $q->whereDate('created_at', now()),
                'last_week' => $q->whereBetween('created_at', [now()->subWeek(), now()]),
                'last_month' => $q->whereMonth('created_at', now()->subMonth()->month),
                default => $q
            };
        });

        return DataTables::of($query)
                    ->addColumn('company', fn ($url) => $url->company_name ?? 'N/A')
                    ->addIndexColumn()
                    ->toJson();
    }
}
