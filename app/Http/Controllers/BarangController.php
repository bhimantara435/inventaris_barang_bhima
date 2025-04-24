<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel; // Menggunakan model Barang
use App\Models\KategoriModel; // Menggunakan model Kategori
use Yajra\DataTables\Facades\DataTables; // Menggunakan package DataTables untuk menampilkan data dalam bentuk tabel

class BarangController extends Controller
{
    // Menampilkan halaman index barang
    public function index()
    {
        // Data breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Barang',
            'list' => ['Dashboard' => 'Barang']
        ];

        // Judul halaman
        $page = (object) [
            'title' => 'Barang'
        ];

        // Menandai menu yang sedang aktif
        $activeMenu = 'barang';

        // Mengambil semua kategori untuk ditampilkan pada view
        $kategories = KategoriModel::all();

        // Menampilkan view barang.index dengan data yang diperlukan
        return view('barang.index', compact('page', 'breadcrumb', 'activeMenu', 'kategories'));
    }

    // Mengambil data barang untuk ditampilkan pada DataTables
    public function list(Request $request)
    {
        // Mengambil data barang beserta relasi kategori
        $barangs = BarangModel::with('kategori')->select('id', 'nama_barang', 'kategori', 'harga', 'stok', 'created_at', 'updated_at');

        // Mengembalikan data dalam format yang bisa dibaca DataTables
        return DataTables::of($barangs)
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('action', function ($barang) {
                // Menambahkan tombol Edit dan Hapus di kolom aksi
                $btn = '<button onclick="modalAction(\'' . route('barang.edit', $barang->id) . '\')" class="btn btn-warning btn-sm btn-edit">Edit</button> ';
                $btn .= '<button onclick="confirmDelete(' . $barang->id . ')" class="btn btn-danger btn-sm btn-delete">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action']) // Menandai kolom aksi sebagai HTML
            ->make(true); // Mengembalikan response JSON
    }

    // Menyimpan data barang baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|integer',
            'harga' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        try {
            // Menyimpan data ke database
            BarangModel::create([
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'harga' => $request->harga,
                'stok' => $request->stok
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Jika gagal menyimpan data, kembalikan error 500
            return response()->json(['success' => false], 500);
        }
    }

    // Menampilkan data barang yang akan diedit
    public function edit($id)
    {
        // Mengambil data barang berdasarkan id beserta kategori
        $barang = BarangModel::with('kategori')->find($id);
        // Mengambil semua kategori
        $kategories = KategoriModel::all();

        // Menampilkan view edit dengan data barang dan kategori
        return view('barang.edit', compact('barang', 'kategories'));
    }

    // Mengupdate data barang
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|integer',
            'harga' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        try {
            // Mengupdate data barang berdasarkan id
            $barang = BarangModel::find($id);
            $barang->update([
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'harga' => $request->harga,
                'stok' => $request->stok
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Jika gagal mengupdate data, kembalikan error 500
            return response()->json(['success' => false], 500);
        }
    }

    // Menghapus data barang
    public function destroy($id)
    {
        try {
            // Menghapus barang berdasarkan id
            $barang = BarangModel::find($id);
            $barang->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Jika gagal menghapus data, kembalikan error 500 dengan pesan
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
