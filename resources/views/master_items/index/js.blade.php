<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\KategoriItem;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        $kategoriItems = KategoriItem::orderBy('nama')->get();
        return view('master_items.index.index', compact('kategoriItems'));
    }

    public function search(Request $request)
    {
        $kode       = $request->kode;
        $nama       = $request->nama;
        $hargamin   = $request->hargamin;
        $hargamax   = $request->hargamax;
        $kategoriId = $request->kategori;

        $data_search = MasterItem::with('kategori'); // eager load

        if (!empty($kode)) {
            $data_search->where('kode', $kode);
        }
        if (!empty($nama)) {
            $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        // ✅ Filter harga
        if (!empty($hargamin) && !empty($hargamax)) {
            $data_search->whereBetween('harga_beli', [$hargamin, $hargamax]);
        } elseif (!empty($hargamin)) {
            $data_search->where('harga_beli', '>=', $hargamin);
        } elseif (!empty($hargamax)) {
            $data_search->where('harga_beli', '<=', $hargamax);
        }

        // ✅ Filter kategori (many-to-many)
        if (!empty($kategoriId)) {
            $data_search->whereHas('kategori', function($q) use ($kategoriId) {
                $q->where('kategori_items.id', $kategoriId);
            });
        }

        $data_search = $data_search->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')
                                   ->orderBy('id')
                                   ->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        $kategoriItems = KategoriItem::orderBy('nama')->get();

        if ($method == 'new') {
            $item = new MasterItem();
            $selectedKategori = [];
        } else {
            $item = MasterItem::with('kategori')->find($id);
            $selectedKategori = $item->kategori->pluck('id')->toArray();
        }

        return view('master_items.form.index', [
            'item' => $item,
            'method' => $method,
            'kategoriItems' => $kategoriItems,
            'selectedKategori' => $selectedKategori
        ]);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategori')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required|string',
            'jenis' => 'required|string',
            'kategori' => 'array', // kategori multiple
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id') + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(1);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama       = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba       = $request->laba;
        $data_item->kode       = $kode;
        $data_item->supplier   = $request->supplier;
        $data_item->jenis      = $request->jenis;

        // ✅ Upload foto
        if ($request->hasFile('foto')) {
            $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('public/master_items', $filename);
            $data_item->foto = $filename;
        }

        $data_item->save();

        // ✅ Sync kategori many-to-many
        if ($request->filled('kategori')) {
            $data_item->kategori()->sync($request->kategori);
        } else {
            $data_item->kategori()->detach();
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = str_pad($item->id, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        return $array[array_rand($array)];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        return $array[array_rand($array)];
    }
}
