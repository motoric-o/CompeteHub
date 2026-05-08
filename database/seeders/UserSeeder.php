<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Committee / Panitia
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Valentino Hose',
                'email'             => 'valentino@competehub.com',
                'password'          => Hash::make('password'),
                'role'              => 'committee',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Felicia Ivanna',
                'email'             => 'felicia@competehub.com',
                'password'          => Hash::make('password'),
                'role'              => 'committee',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // Judge / Juri
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Jeryko Farelin',
                'email'             => 'jeryko@competehub.com',
                'password'          => Hash::make('password'),
                'role'              => 'judge',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Rico Dharmawan',
                'email'             => 'rico@competehub.com',
                'password'          => Hash::make('password'),
                'role'              => 'judge',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // Participants / Peserta
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Budi Santoso',
                'email'             => 'budi@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'participant',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Siti Rahayu',
                'email'             => 'siti@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'participant',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Andi Wijaya',
                'email'             => 'andi@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'participant',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Dewi Kusuma',
                'email'             => 'dewi@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'participant',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Reza Pratama',
                'email'             => 'reza@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'participant',
                'status'            => 'suspended', // untuk test AccountStatusHandler
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Nina Kartika',
                'email'             => 'nina@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'participant',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
