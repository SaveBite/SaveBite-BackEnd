<?php

namespace Database\Seeders;

use App\Models\LoginQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoginQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoginQuestion::query()->create([
            'content'=>'What is your favorite drink?'
        ]);
    }
}
