<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ User, ShortUrl };
use Illuminate\Support\Facades\{Auth, DB};
use Yajra\DataTables\Facades\DataTables;


class TeamResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // user_id -> total urls + total hits
        $userUrlMetrics = ShortUrl::query()
                        ->selectRaw("user_id, COUNT(*) as total_urls, COALESCE(SUM(hits), 0) as total_hits")
                        ->where('company_id', $companyId)
                        ->groupBy('user_id');

        //user + roles + userUrls
        $users = User::query()
                    ->where('users.company_id', $companyId)
                    ->leftJoinSub($userUrlMetrics, 'url_metrics', function ($join) {
                        $join->on('users.id', '=', 'url_metrics.user_id');
                    })
                    ->leftJoin('model_has_roles', function ($join) {
                        $join->on('users.id', '=', 'model_has_roles.model_id')
                        ->where('model_has_roles.model_type', User::class);
                    })
                    ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->select([
                        'users.id',
                        'users.name',
                        'users.email',
                        'roles.name as role',
                        DB::raw('COALESCE(url_metrics.total_urls, 0) as total_urls'),
                        DB::raw('COALESCE(url_metrics.total_hits, 0) as total_hits'),
                    ]);

        return DataTables::of($users)->make(true);
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
     * list on generated short urls by team members
     * 
     * @return DataTable
     */
    public function teamGeneratedUrls(Request $request) 
    {
    $companyId = auth()->user()->company_id;

    $query = ShortUrl::query()
        ->where('short_urls.company_id', $companyId)
        ->leftJoin('users', 'users.id', '=', 'short_urls.user_id')
        ->select([
            'short_urls.id',
            'short_urls.original_url',
            'short_urls.short_code',
            'short_urls.hits',
            'short_urls.created_at',
            'users.name as created_by',
        ]);

    // Filter
    $query->when($request->filter, function ($q, $filter) {
        return match ($filter) {
            'today' => $q->whereDate('short_urls.created_at', now()),
            'last_week' => $q->whereBetween('short_urls.created_at', [now()->subWeek(), now()]),
            'last_month' => $q->whereMonth('short_urls.created_at', now()->subMonth()->month),
            default => $q,
        };
    });

    return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('short_url', function ($row) {
            return url($row->short_code);
        })
        ->editColumn('created_at', function ($row) {
            return optional($row->created_at)->format('Y-m-d');
        })
        ->rawColumns(['short_url'])
        ->make(true);

    }

}
