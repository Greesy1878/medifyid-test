<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoToMasterItemsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('master_items', 'foto')) {
            Schema::table('master_items', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('nama');
            });
        }
    }


    public function down()
    {
        Schema::table('master_items', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
}
