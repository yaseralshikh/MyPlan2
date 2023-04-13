<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'              => 'ياسر محمد أحمد الشيخ',
            'email'             => 'yaseralshikh@gmail.com',
            'specialization_id' => 17,
            'office_id'         => 1,
            'job_type_id'       => 1,
            'section_type_id'   => 1,
            'gender'            => 1,
            'password'          => bcrypt('123123123'),
            'status'            => 1,
            'email_verified_at' => now(),
        ]);

        $user->addRole('superadmin');
    }
}
