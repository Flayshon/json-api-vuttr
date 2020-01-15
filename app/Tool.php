<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tool extends Model
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
}
