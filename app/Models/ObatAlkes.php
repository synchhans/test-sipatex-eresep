<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatAlkes extends Model
{
    use HasFactory;
    protected $table = 'obatalkes_m';
    protected $primaryKey = 'obatalkes_id';
    public $timestamps = false;
    protected $fillable = ['stok'];
}
