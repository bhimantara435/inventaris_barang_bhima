<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory; // Trait untuk mendukung factory pada model ini

    // Menentukan nama tabel yang digunakan oleh model ini
    protected $table = 'barangs';

    // Menentukan primary key dari tabel
    protected $primaryKey = 'id';

    // Kolom-kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'nama_barang', // Nama barang
        'kategori',    // ID kategori (relasi foreign key ke tabel kategori)
        'harga',       // Harga barang
        'stok',        // Jumlah stok barang
        'created_at',  // Timestamp saat dibuat
        'updated_at'   // Timestamp saat diperbarui
    ];

    // Relasi: Barang milik satu Kategori
    public function kategori()
    {
        // Relasi belongsTo berarti foreign key 'kategori' di tabel 'barangs' mengacu ke 'id' di tabel 'kategoris'
        return $this->belongsTo(KategoriModel::class, 'kategori', 'id');
    }
}
