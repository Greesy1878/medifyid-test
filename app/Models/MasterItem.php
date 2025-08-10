<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MasterItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Relasi Many-to-Many ke KategoriItem
     */
    public function kategori()
    {
        // Asumsikan tabel pivot bernama kategori_item_master_item
        // dan foreign key-nya adalah master_item_id dan kategori_item_id
        return $this->belongsToMany(KategoriItem::class, 'kategori_item_master_item', 'master_item_id', 'kategori_item_id');
    }
}
