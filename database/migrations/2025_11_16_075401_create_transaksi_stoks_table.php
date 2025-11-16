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
        Schema::create('transaksi_stok', function (Blueprint $table) {
            $table->id('id_transaksi'); // Sesuai ERD kamu

            // FK ke tabel barang
            $table->foreignId('id_barang')->constrained('barang', 'id_barang');

            // FK ke tabel users (pengganti pegawai)
            $table->foreignId('id_pegawai')->constrained('users', 'id');

            // FK ke tabel pemasok
            $table->foreignId('id_pemasok')->nullable()->constrained('pemasok', 'id_pemasok');

            $table->enum('transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->text('keterangan');
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
        Schema::dropIfExists('transaksi_stoks');
    }
};
