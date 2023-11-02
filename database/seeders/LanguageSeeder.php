<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now=now();
        Language::insert([
            ['name'=>'German', 'code'=> 'de', 'created_at'=>$now, 'updated_at'=>$now],
            ['name'=>'English', 'code'=> 'en', 'created_at'=>$now, 'updated_at'=>$now],
            // ['name'=>'French', 'code'=> 'fr', 'created_at'=>$now, 'updated_at'=>$now],
            // ['name'=>'Italian', 'code'=> 'it', 'created_at'=>$now, 'updated_at'=>$now],
        ]);
    }
}
