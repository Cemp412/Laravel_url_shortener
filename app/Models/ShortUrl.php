<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'user_id', 'original_url', 'short_code', 'hits'];

    //relation with company
    public function company() {
        return $this->belongsTo(Company::class);
    }

    //relation with user
    public function user() {
        return $this->belongsTo(User::class);
    }

    //scope-visibility
    // public function scope
}
