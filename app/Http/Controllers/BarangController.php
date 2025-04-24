<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Barang',
            'list' => ['Dashboard' => 'Barang']
        ];

        $page = (object) [
            'title' => 'Barang'
        ];
        $activeMenu = 'barang';
        $kategories = KategoriModel::all();
        return view('barang.index', compact('page', 'breadcrumb', 'activeMenu', 'kategories'));
    }

    public function list(Request $request)
    {
        $barangs = BarangModel::with('kategori')->select('id', 'nama_barang', 'kategori', 'harga', 'stok', 'created_at', 'updated_at');

        return DataTables::of($barangs)
            ->addIndexColumn()
            ->addColumn('action', function ($barang) {
                $btn = '<button onclick="modalAction(\'' . route('barang.edit', $barang->id) . '\')" class="btn btn-warning btn-sm btn-edit">Edit</button> ';
                $btn .= '<button onclick="confirmDelete(' . $barang->id . ')" class="btn btn-danger btn-sm btn-delete">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|integer',
            'harga' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        try {
            BarangModel::create([
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'harga' => $request->harga,
                'stok' => $request->stok
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function edit($id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        $kategories = KategoriModel::all();
        return view('barang.edit', compact('barang', 'kategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|integer',
            'harga' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        try {
            $barang = BarangModel::find($id);
            $barang->update([
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'harga' => $request->harga,
                'stok' => $request->stok
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $barang = BarangModel::find($id);
            $barang->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
