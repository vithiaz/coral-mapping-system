<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $AdminUser = new User;
        $AdminUser->name = 'Admin';
        $AdminUser->username = 'admin';
        $AdminUser->email = 'admin@mail.com';
        $AdminUser->password = Hash::make('admin1234');
        $AdminUser->save();

        

    }
}
