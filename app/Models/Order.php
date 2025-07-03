<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Assuming your table is named 'orders'

    protected $primaryKey = 'id_pesanan';

    public $incrementing = true;

    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'email',
        'no_telepon',
        'metode_bayar',
        'tanggal_pesan',
        'status',
        'nota',
        'desain',
        'ukuran',
        'total',
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'total' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}