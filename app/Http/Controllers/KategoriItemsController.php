<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use App\Models\MasterItem;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        // Mengambil semua kategori untuk kebutuhan filter di view
        $kategoriItems = KategoriItem::orderBy('nama')->get();

        return view('kategori_items.index', compact('kategoriItems'));
    }

    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $query = KategoriItem::query();

        if (!empty($nama)) {
            $query->where('nama', 'LIKE', '%' . $nama . '%');
        }
        if (!empty($kode)) {
            $query->where('kode', 'LIKE', '%' . $kode . '%');
        }

        $data = $query->withCount('masterItems')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function formView($method, $id = 0)
    {
        $kategori = $method === 'edit'
            ? KategoriItem::with('masterItems')->findOrFail($id)
            : new KategoriItem();

        $items = MasterItem::all();

        return view('kategori_items.form', compact('kategori', 'items', 'method'));
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategori_items,kode,' . $id,
            'items' => 'array'
        ]);

        if ($method === 'new') {
            $kategori = KategoriItem::create($request->only('kode', 'nama'));
        } else {
            $kategori = KategoriItem::findOrFail($id);
            $kategori->update($request->only('kode', 'nama'));
        }

        // Sync many-to-many relation dengan masterItems
        $kategori->masterItems()->sync($request->items ?? []);

        return redirect('kategori-items');
    }

    public function singleView($id)
    {
        $kategori = KategoriItem::with('masterItems')->findOrFail($id);
        return view('kategori_items.show', compact('kategori'));
    }

    public function delete($id)
    {
        KategoriItem::findOrFail($id)->delete();
        return redirect('kategori-items');
    }
}
