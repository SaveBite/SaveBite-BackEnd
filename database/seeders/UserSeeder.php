<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'user_name' => 'User',
            'email' => 'user@elryad.com',
            'password' => 'elryad1256!#',
            'phone'=>'01062535652',
            'type'=>'user',
        ]);
    }
}
