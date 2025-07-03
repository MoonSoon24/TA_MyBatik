<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPesanan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pesanans';

    protected $fillable = [
        'order_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        // The second argument 'user_id' is the foreign key in the 'riwayat_pesanans' table.
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
