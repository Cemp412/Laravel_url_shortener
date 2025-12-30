<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'email'];

    //relation with users
    public function users() {
        return $this->hasMany(User::class);
    }

    //relation with invitations
    public function invitations() {
        return $this->hasMany(Invitation::class);
    }

    //relation with shortUrls
    public function shortUrls() {
        return $this->hasMany(ShortUrl::class);
    }
}
