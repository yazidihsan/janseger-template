<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'user_id' => 'required',
            'total_item' => 'required',
            'total_harga' => 'required'
        ]);

        if ($validasi->fails())
        {

            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }


        $kode_payment = "INV/PYM/".now()->format('Y-m-d')."/".rand(100, 999);
        $kode_trx = "INV/PYM/".now()->format('Y-m-d')."/".rand(100, 999);
        $kode_unik = rand(100, 999);
        $status = "PENDING";
        $expired_at = now()->addDay();

        $dataTransaksi = array_merge($request->all(), [
            'kode_payment' => $kode_payment,
            'kode_trx' => $kode_trx,
            'kode_unik' => $kode_unik,
            'status' => $status,
            'expired_at' => $expired_at
        ]);

        \DB::beginTransaction();
        $transaksi = Transaction::create($dataTransaksi);
        foreach($request->products as $product)
        {
            $detail = [
                'transaksi_id' => $transaksi->id,
                'produk_id' => $product['id'],
                'total_item' => $product['total_item'],
                'total_harga' => $product['total_harga']
            ];
            $transaksiDetail = TransactionDetail::create($detail);
        }

        if (!empty($transaksi) && !empty($transaksiDetail))
        {
            \DB::commit();
            return ResponseFormatter::success([
                'success' => true,
                'transaction' => collect($transaksi)
            ], 'Transaksi Berhasil');
        } else {
            \DB::rollback();
            return ResponseFormatter::error('Transaksi Gagal');
        }

    }
    public function history($id)
    {
        $histories = Transaction::with(['user'])->whereHas('user', function ($query) use ($id){
            $query->whereId($id);
        })->get();

        foreach($histories as $history)
        {
            $details = $history->details;
            foreach($details as $detail)
            {
                $detail->product;
            }
        }

        if(!empty($history))
        {
            return ResponseFormatter::success([
                'success' => true,
                'transaction' => collect($history)
            ], 'Data Transaksi Berhasil Diambil');
        }else {
            return ResponseFormatter::error('Data Transaksi kosong');
        }
    }
}
