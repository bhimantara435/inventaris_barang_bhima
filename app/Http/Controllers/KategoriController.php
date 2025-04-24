<?php

// Menetapkan namespace untuk controller
namespace App\Http\Controllers;

// Mengimpor class-class yang diperlukan
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    // Menampilkan halaman utama kategori
    public function index()
    {
        // Breadcrumb untuk tampilan navigasi di atas halaman
        $breadcrumb = (object) [
            'title' => 'Kategori',
            'list' => ['Dashboard' => 'Kategori']
        ];

        // Informasi untuk judul halaman
        $page = (object) [
            'title' => 'Kategori Barang'
        ];

        // Menentukan menu yang sedang aktif di sidebar
        $activeMenu = 'kategori';

        // Mengambil semua data kategori dari database
        $kategori = KategoriModel::all();

        // Mengirimkan data ke view kategori.index
        return view('kategori.index', compact('page', 'kategori', 'breadcrumb', 'activeMenu'));
    }

    // Mengambil data kategori dalam format yang dapat digunakan oleh DataTables
    public function list(Request $request)
    {
        // Mengambil kolom-kolom tertentu dari tabel kategori
        $kategori = KategoriModel::select('id', 'nama_kategori', 'created_at', 'updated_at');

        // Memformat data untuk DataTables
        return DataTables::of($kategori)
            ->addIndexColumn() // Menambahkan nomor urut pada setiap baris
            ->addColumn('action', function ($kategori) {
                // Tombol Edit dengan trigger modalAction() dan route ke edit
                $btn = '<button onclick="modalAction(\'' . route('kategori.edit', $kategori->id) . '\')" class="btn btn-warning btn-sm btn-edit">Edit</button> ';
                // Tombol Hapus dengan konfirmasi
                $btn .= '<button onclick="confirmDelete(' . $kategori->id . ')" class="btn btn-danger btn-sm btn-delete">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action']) // Mengizinkan HTML dalam kolom 'action'
            ->make(true); // Mengembalikan response JSON
    }

    // Menyimpan data kategori baru
    public function store(Request $request)
    {
        // Validasi input agar nama kategori tidak kosong dan maksimal 255 karakter
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        try {
            // Menyimpan data ke database
            KategoriModel::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            // Mengembalikan response sukses
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Jika ada error saat menyimpan, kirimkan response error
            return response()->json(['success' => false], 500);
        }
    }

    // Menampilkan form edit kategori berdasarkan ID
    public function edit($id)
    {
        // Mengambil data kategori berdasarkan ID
        $kategori = KategoriModel::find($id);

        // Menampilkan view kategori.edit dengan data kategori
        return view('kategori.edit', compact('kategori'));
    }

    // Memperbarui data kategori
    public function update(Request $request, $id)
    {
        // Validasi input sebelum update
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        try {
            // Cari data kategori berdasarkan ID
            $kategori = KategoriModel::find($id);

            // Update data kategori
            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            // Response sukses
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Response gagal jika update bermasalah
            return response()->json(['success' => false], 500);
        }
    }

    // Menghapus data kategori berdasarkan ID
    public function destroy($id)
    {
        try {
            // Cari dan hapus data
            $kategori = KategoriModel::find($id);
            $kategori->delete();

            // Response sukses
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Response gagal jika delete error
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
