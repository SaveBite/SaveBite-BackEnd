<?php

namespace Database\Seeders;

use App\Models\LoginAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoginAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr=['tea','coffee','milk'];
        foreach ($arr as $item){
            LoginAnswer::query()->insert([
                'login_question_id'=>1,
                'content'=>$item
            ]);
        }


    }
}
