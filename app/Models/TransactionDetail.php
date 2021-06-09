<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaksi_id', 'produk_id', 'total_item', 'total_harga'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaksi_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'produk_id','id');
    }
}
