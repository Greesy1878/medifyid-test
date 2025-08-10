<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterItem;


class KategoriItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi Many-to-Many ke MasterItem
     */
    public function masterItems()
    {
        // Asumsikan tabel pivot bernama kategori_item_master_item
        // dan foreign key-nya adalah kategori_item_id dan master_item_id
        return $this->belongsToMany(MasterItem::class, 'kategori_item_master_item', 'kategori_item_id', 'master_item_id');
    }
}
