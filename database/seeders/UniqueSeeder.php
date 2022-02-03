<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($name, $email, $password)
    {
        $seed = DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if($seed){
            echo "User {$email} created!\n\n";
        }
    }
}
