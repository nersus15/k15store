<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $product = [];
        for ($i=1; $i <= 8; $i++) { 
            for ($j=1; $j <= 5; $j++) { 
                $stok = $faker->randomNumber(2);
                $product[] = [
                    'id' => Str::random(5),
                    'owner' => 'user' . (5 + $j) . '-k15store',
                    'nama_product' => 'product' . ($i * $j),
                    'deskripsi' => $faker->paragraph(),
                    'harga' => $faker->randomNumber(6),
                    'stok' => $stok,
                    'kategori' => 'kategori' . $i,
                    'batas_beli' => $stok,
                    'berat' => $faker->randomNumber(3)
                ];
            }
        }
        DB::table('product')->insert($product);
    }
}
