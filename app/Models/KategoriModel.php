<?php

// Namespace untuk mengatur lokasi file model dalam struktur folder Laravel
namespace App\Models;

// Mengimpor trait dan class yang diperlukan dari Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    // Menggunakan trait HasFactory untuk memungkinkan penggunaan factory saat testing atau seeding
    use HasFactory;

    // Menentukan nama tabel yang digunakan oleh model ini
    protected $table = 'kategoris';

    // Menentukan primary key dari tabel
    protected $primaryKey = 'id';

    // Menentukan field-field yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'nama_kategori',    // Nama kategori (wajib diisi oleh user)
        'created_at',       // Tanggal pembuatan (biasanya otomatis)
        'updated_at'        // Tanggal update terakhir (juga otomatis)
    ];
}
