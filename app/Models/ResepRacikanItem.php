<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepRacikanItem extends Model
{
    use HasFactory;
    protected $table = 'resep_racikan_item';
    protected $fillable = ['resep_item_id', 'obatalkes_id', 'jumlah'];

    public function resepItem()
    {
        return $this->belongsTo(ResepItem::class);
    }

    public function obat()
    {
        return $this->belongsTo(ObatAlkes::class, 'obatalkes_id', 'obatalkes_id');
    }
}
