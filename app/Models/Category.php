<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'img'
    ];

    public function products()
    {
       return $this->hasMany(Product::class,'product_id','id');
    }

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['img'] = $this->img;
        return $toArray;
    }

    public function getCreatedAtAttribute($created_at)
    {
        return Carbon::parse($created_at)
            ->getPreciseTimestamp(3);
    }
    public function getUpdatedAtAttribute($updated_at)
    {
        return Carbon::parse($updated_at)
            ->getPreciseTimestamp(3);
    }
    public function getPicturePathAttribute()
    {
        return url('') . Storage::url($this->attributes['img']);
    }
}
