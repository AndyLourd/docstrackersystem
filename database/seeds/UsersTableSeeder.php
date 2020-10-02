<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('dongka'),
                'user_type' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Supply',
                'email' => 'supply@supply.com',
                'email_verified_at' => now(),
                'password' => Hash::make('supply'),
                'user_type' => 'Supply',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'email_verified_at' => now(),
                'password' => Hash::make('user'),
                'user_type' => 'End User',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@accountant.com',
                'email_verified_at' => now(),
                'password' => Hash::make('accountant'),
                'user_type' => 'Accountant',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HRMO',
                'email' => 'hrmo@hrmo.com',
                'email_verified_at' => now(),
                'password' => Hash::make('hrmo'),
                'user_type' => 'HRMO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TOD',
                'email' => 'tod@tod.com',
                'email_verified_at' => now(),
                'password' => Hash::make('tod'),
                'user_type' => 'TOD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'OOTD',
                'email' => 'ootd@ootd.com',
                'email_verified_at' => now(),
                'password' => Hash::make('ootd'),
                'user_type' => 'OOTD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Canvasser',
                'email' => 'canvasser@canvasser.com',
                'email_verified_at' => now(),
                'password' => Hash::make('canvasser'),
                'user_type' => 'Canvasser',
                'created_at' => now(),
                'updated_at' => now()
            ]

        ]);
        DB::table('usertypes')->insert([
            [
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'End User',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Supply',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Canvasser',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'OOTD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TOD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Accountant',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cashier',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HRMO',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
