<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;

class PublicShortUrlController extends Controller
{
    public function redirect(string $code) {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();
        $shortUrl->increment('hits');

        return redirect()->away($shortUrl->original_url);
    }
}
