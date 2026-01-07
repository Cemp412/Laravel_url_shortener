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

        
        return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return ucfirst($row->name);
                })
                 ->addColumn('total_hits', function ($row) {
                    return $row->total_hits ?? 0;
                })
        ->make(true);
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
        $query->when($request->filter, function ($q, $filter) {
            return match ($filter) {
                'today' => $q->whereDate('short_urls.created_at', today()),
                'last_week' => $q->whereBetween('short_urls.created_at', [
                    now()->subDays(7)->startOfDay(), 
                    now()->endOfDay()
                ]),
                'this_month' => $q->whereBetween('short_urls.created_at', [
                    now()->startOfMonth(), 
                    now()->endOfDay()
                ]),
                'last_month' => $q->whereMonth('short_urls.created_at', [
                    now()->subMonth()->startOfMonth(), 
                    now()->subMonth()->endOfMonth()
                ]),
                default => $q,
            };
        });

        return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('short_url', function ($row) {
                        return url($row->short_code);
                    })
                    ->addColumn('company', fn ($row) => ucfirst($row->company->name) ?? 'N/A')
                    ->toJson();
    }
}
