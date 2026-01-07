<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortCodeGenerator
{
    public static function generate():string 
    {
        do{
            $code = Str::random(6);
        }while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}