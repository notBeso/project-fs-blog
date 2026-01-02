<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $timestamps = false;

    protected $fillable = [

        'title',
        'des',
        'detail',
        'category',
        'public',
        'data_public',
        'position',
        'thumbs',
    ];

    protected $casts = [
        'position' => 'array',
        'thumbs' => 'array',
        'public' => 'boolean',
    ];

}
