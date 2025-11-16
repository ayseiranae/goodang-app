<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang'); // Sesuai ERD kamu

            // Ini cara buat FK di Laravel
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori');
            $table->foreignId('id_pemasok')->nullable()->constrained('pemasok', 'id_pemasok');

            $table->string('barang', 45);
            $table->text('deskripsi');
            $table->string('satuan', 10); // Ganti dari enum, lebih fleksibel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barangs');
    }
};
