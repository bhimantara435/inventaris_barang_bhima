<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kategori',
            'list' => ['Dashboard' => 'Kategori']
        ];

        $page = (object) [
            'title' => 'Kategori Barang'
        ];
        $activeMenu = 'kategori';
        $kategori = KategoriModel::all();
        return view('kategori.index', compact('page', 'kategori', 'breadcrumb', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $kategori = KategoriModel::select('id', 'nama_kategori', 'created_at', 'updated_at');

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('action', function ($kategori) {
                $btn = '<button onclick="modalAction(\'' . route('kategori.edit', $kategori->id) . '\')" class="btn btn-warning btn-sm btn-edit">Edit</button> ';
                $btn .= '<button onclick="confirmDelete(' . $kategori->id . ')" class="btn btn-danger btn-sm btn-delete">Hapus</button>';
                return $btn;
            })->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        try {
            KategoriModel::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function edit($id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        try {
            $kategori = KategoriModel::find($id);
            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kategori = KategoriModel::find($id);
            $kategori->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }
}
