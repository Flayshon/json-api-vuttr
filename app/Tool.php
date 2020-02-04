<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tool extends Model implements JWTSubject
{
    protected $fillable = [
        'title',
        'link',
        'description',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function path()
    {
        return "/tools/{$this->id}";
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
