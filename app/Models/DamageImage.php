<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageImage extends Model
{
    protected $table = "damage_images";

    protected $fillable = [
        'user_id',
        'house_id',
        'title',
        'image_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
