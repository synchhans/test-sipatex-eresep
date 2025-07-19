<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;
    protected $table = 'resep';
    protected $fillable = ['nomor_resep'];

    public function items()
    {
        return $this->hasMany(ResepItem::class);
    }
}
