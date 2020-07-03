<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        foreach (range(1, 100) as $i) {
            User::create([
                // 'login' => 'seed-' . $i,
                'password' => Hash::make(uniqid()),
            ]);
        };
        Schema::enableForeignKeyConstraints();
    }
}
