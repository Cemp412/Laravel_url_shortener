<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\ShortUrl;
use App\Services\ShortCodeGenerator;
use Validator;

class ShortUrlController extends BaseController
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
       $this->authorize('create', ShortUrl::class);

        $validator = Validator::make($request->all(),[
            'original_url' => 'required|url|max:2048',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = auth()->user();

        //create shorturl
        $shortUrl = ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $request->original_url,
            'short_code' => ShortCodeGenerator::generate(),
        ]);

        $success['short_url'] = url($shortUrl->short_code);
        $success['original_url'] = $shortUrl->original_url;
        return $this->sendResponse($success, 'Short Url Created Successfully', 201);
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
}
