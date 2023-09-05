<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    protected $fillable = [
        'title', 'description', 'show_related', 'user_id', 'image', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getImageAttribute()
    {
        if($this->attributes['image'])
        {
            return env('APP_URL') . Storage::url($this->attributes['image']);
        }
        else
        {
            return null;
        }
    }


}
