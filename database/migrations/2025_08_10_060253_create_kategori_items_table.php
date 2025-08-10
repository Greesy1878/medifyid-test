<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriItemsTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 255);
            $table->timestamps();
        });

        // Tabel pivot many-to-many
        Schema::create('kategori_item_master_item', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_item_id');
            $table->unsignedBigInteger('master_item_id');

            $table->foreign('kategori_item_id')->references('id')->on('kategori_items')->onDelete('cascade');
            $table->foreign('master_item_id')->references('id')->on('master_items')->onDelete('cascade');

            $table->primary(['kategori_item_id', 'master_item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_item_master_item');
        Schema::dropIfExists('kategori_items');
    }
}
