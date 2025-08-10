<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\KategoriItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterItemsController extends Controller
{
    public function index()
    {
        // Ambil semua kategori untuk filter dropdown
        $kategori = KategoriItem::orderBy('nama')->get();

        // Ambil semua master items sekaligus relasi kategori
        $items = MasterItem::with('kategori')->orderBy('id')->get();

        // Kirim ke view
        return view('master_items.index.index', compact('kategori', 'items'));
    }

    public function search(Request $request)
    {
        $kode      = $request->kode;
        $nama      = $request->nama;
        $hargamin  = $request->hargamin;
        $hargamax  = $request->hargamax;
        $kategoriId = $request->kategori_id;

        $data_search = MasterItem::with('kategori');

        if (!empty($kode)) {
            $data_search->where('kode', $kode);
        }

        if (!empty($nama)) {
            $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if (!empty($hargamin) && !empty($hargamax)) {
            $data_search->whereBetween('harga_beli', [$hargamin, $hargamax]);
        } elseif (!empty($hargamin)) {
            $data_search->where('harga_beli', '>=', $hargamin);
        } elseif (!empty($hargamax)) {
            $data_search->where('harga_beli', '<=', $hargamax);
        }

        if (!empty($kategoriId)) {
            $data_search->whereHas('kategori', function ($q) use ($kategoriId) {
                $q->where('kategori_items.id', $kategoriId);
            });
        }

        $data_search = $data_search
            ->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        $kategori = KategoriItem::orderBy('nama')->get();
        $item = $method === 'new' ? null : MasterItem::with('kategori')->findOrFail($id);

        return view('master_items.form.index', compact('item', 'method', 'kategori'));
    }

    public function singleView($kode)
    {
        $data = MasterItem::with('kategori')->where('kode', $kode)->firstOrFail();

        return view('master_items.single.index', ['data' => $data]);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|integer|min:0',
            'laba' => 'required|integer|min:0',
            'supplier' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'kategori_id' => 'nullable|array',
            'kategori_id.*' => 'exists:kategori_items,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($method === 'new') {
            $data_item = new MasterItem;
            // Membuat kode unik berdasarkan count + 1, dengan padding 5 digit
            $kode = str_pad(MasterItem::count() + 1, 5, '0', STR_PAD_LEFT);
            sleep(1); // delay supaya kode unik (bisa dipertimbangkan ulang)
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->kode = $kode;
        $data_item->nama = $validatedData['nama'];
        $data_item->harga_beli = $validatedData['harga_beli'];
        $data_item->laba = $validatedData['laba'];
        $data_item->supplier = $validatedData['supplier'];
        $data_item->jenis = $validatedData['jenis'];

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($data_item->foto && Storage::disk('public')->exists($data_item->foto)) {
                Storage::disk('public')->delete($data_item->foto);
            }
            $data_item->foto = $request->file('foto')->store('master_items', 'public');
        }

        $data_item->save();

        // Sync relasi kategori many-to-many
        if (!empty($validatedData['kategori_id'])) {
            $data_item->kategori()->sync($validatedData['kategori_id']);
        } else {
            $data_item->kategori()->detach();
        }

        return redirect()->route('master-items.index'); // gunakan route name jika ada
    }

    public function delete($id)
    {
        $item = MasterItem::findOrFail($id);

        // Hapus file foto jika ada
        if ($item->foto && Storage::disk('public')->exists($item->foto)) {
            Storage::disk('public')->delete($item->foto);
        }

        $item->delete();

        return redirect()->route('master-items.index'); // pakai route name
    }

    public function updateRandomData()
    {
        $data = MasterItem::all();
        foreach ($data as $item) {
            $item->kode = str_pad($item->id, 5, '0', STR_PAD_LEFT);
            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
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
