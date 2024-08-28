<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_entries', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kapal');
            $table->string('no_permintaan');
            $table->date('tgl_permintaan');
            $table->string('jenis_barang');
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_entries');
    }
};
