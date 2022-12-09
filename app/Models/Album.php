<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Album extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];


    /**
     * Get the user that owns the album.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}
