<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use phpDocumentor\Reflection\Types\Null_;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create('id_ID');
        $users = [];
        $user_info = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'username' => 'user' . $i .  '-k15store',
                'email' => 'user' . $i . '@k15store.com',
                'role' => $i > 5 ? 'pedagang' : 'pembeli',
                'password' => Hash::make('passuser' . $i),
                'nohp' => $faker->phoneNumber(),
                'nama_lengkap' => $faker->name(),
                'alamat' => $faker->address(200),
                'kab_kota' => $i > 5 ? $faker->numberBetween(1, 501) : null
            ];
        }
        $users[] = [
            'username' => 'admin-k15store',
            'email' => 'admin@k15store.com',
            'role' => 'admin',
            'password' => Hash::make('kambing15'),
            'nohp' => '083142808426',
            'nama_lengkap' => 'Fathurrahman',
            'alamat' => 'Desa Sukarara, Kec. Sakra Barat, Kab. Lombok Timur',
            'kab_kota' => null
        ];

        DB::table('users')->insert($users);
    }
}
