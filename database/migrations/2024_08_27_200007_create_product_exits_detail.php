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
        Schema::create('product_exits_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_exit_id')->constrained('product_exits')->onDelete('cascade');
            $table->foreignId('product_entrie_detail_id')->constrained('product_entries_detail')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('total');
            $table->date('exit_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_exits_detail');
    }
};
