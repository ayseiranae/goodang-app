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
        Schema::create('pemasok', function (Blueprint $table) {
            $table->id('id_pemasok'); // Sesuai ERD kamu
            $table->string('nama_pemasok', 45);
            $table->string('kontak', 15);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemasoks');
    }
};
