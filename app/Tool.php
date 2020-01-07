<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = [
        'title',
        'link',
        'description',
        'tags',
    ];

    protected $casts = [
        'tags' => 'json',
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
