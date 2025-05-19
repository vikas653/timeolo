<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('students')->insert([
            'first_name' => 'aryan',
            'last_name' => 'saini',
            'email' => 'malikpur',
            'password' => '1234' ,
            'phone' => '9588714464',
        ]);
    }
}
