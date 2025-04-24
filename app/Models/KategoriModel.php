<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_kategori',
        'created_at',
        'updated_at'
    ];
}
