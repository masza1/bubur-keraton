<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            ['name' => 'Operator Bubur Keraton', 'email' => 'buburkeraton@gmail.com', 'email_verified_at' => now(), 'password' => Hash::make('Buburkerat0n'), 'remember_token' => \Str::random(10), 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('item_stocks')->delete();
        DB::table('item_stocks')->insert([
            ['name' => 'Styrofoam', 'type' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paper Bowl', 'type' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bowl Mini', 'type' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hydro Coco', 'type' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Susu', 'type' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Air Mineral', 'type' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Telur Ayam Kampung', 'type' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Telur Asin', 'type' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('items')->delete();
        DB::table('items')->insert([
            ['name' => 'Bubur Ayam - Original', 'price' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bubur Ayam - Nugget', 'price' => 13000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bubur Ayam - Adipati', 'price' => 17000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bubur Ayam - Senopati', 'price' => 20000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bubur Ayam - Telur Asin', 'price' => 16000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bubur Ayam - Ati Empela', 'price' => 17000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Putri Keraton', 'price' => 12000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Putri Keraton Tanpa Ice Cream', 'price' => 8000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Putri Keraton - Froot Loop', 'price' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Putri Keraton - Oreo', 'price' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Putri Keraton - Froot Oreo', 'price' => 18000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Polos', 'price' => 7000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Air Mineral', 'price' => 3000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paper Bowl', 'price' => 2000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Susu Diamond 200ml', 'price' => 6000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Extra Ayam', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Extra Nugget', 'price' => 3000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Extra Telur Ayam Kampung', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Extra Telur Asin', 'price' => 4000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Extra Ati Empela', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
