<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@ged-laravel.com';
        $user->password = bcrypt('asdf000');
        $user->save();
    }
}
