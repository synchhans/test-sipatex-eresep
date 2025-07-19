<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepItem extends Model
{
    use HasFactory;
    protected $table = 'resep_item';
    protected $fillable = ['resep_id', 'jenis', 'obatalkes_id', 'jumlah', 'nama_racikan', 'signa_id'];

    public function resep()
    {
        return $this->belongsTo(Resep::class);
    }

    public function obat()
    {
        return $this->belongsTo(ObatAlkes::class, 'obatalkes_id', 'obatalkes_id');
    }

    public function signa()
    {
        return $this->belongsTo(Signa::class, 'signa_id', 'signa_id');
    }

    public function racikanItems()
    {
        return $this->hasMany(ResepRacikanItem::class);
    }
}
