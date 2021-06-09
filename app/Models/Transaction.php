<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'kode_payment',
    'kode_trx', 'total_item', 'total_harga', 'kode_unik',
    'status', 'bukti_trf', 'expired_at'
];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaksi_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['bukti_trf'] = $this->bukti_trf;
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
        return url('') . Storage::url($this->attributes['bukti_trf']);
    }

}
