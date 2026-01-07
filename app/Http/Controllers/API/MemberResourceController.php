<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Yajra\DataTables\Facades\DataTables;

class MemberResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Short urls list
     * 
     * @return DataTables
     */
    public function shortUrls(Request $request) {
         $user = auth()->user();
        
        $query = ShortUrl::query()
                    ->where('user_id', $user->id)
                    ->select([
                        'id',
                        'original_url as long_url',
                        'short_code',
                        'hits',
                        'created_at'
        ]);

        // Filter
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
                ->editColumn('created_at', function ($row) {
                    return optional($row->created_at)->format('d-M-Y');
                })
                ->rawColumns(['short_url'])
                ->make(true);

    }
}
