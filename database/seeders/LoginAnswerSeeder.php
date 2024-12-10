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
        $arr=['5arbosh shay','Coffee','Mango', 'Farawla', 'Sahlab'];
        foreach ($arr as $item){
            LoginAnswer::query()->insert([
                'content'=>$item,
                'login_question_id'=>1
            ]);
        }

    }
}
