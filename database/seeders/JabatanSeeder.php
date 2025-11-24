<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jabatan')->insert([
            ['id_jabatan' => 1, 'jabatan' => 'Admin'],
            ['id_jabatan' => 2, 'jabatan' => 'Pegawai'],
        ]);
    }
}
