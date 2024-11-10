<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class House extends Model
{
    protected $table = "houses";
    protected $fillable = [
        'user_id',
        'address',
        'description',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function damageImages()
    {
        return $this->hasMany(DamageImage::class);
    }


}
