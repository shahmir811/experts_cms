<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = new User([
            'name' => 'Sami Ullah Ata',
            'email' => 'sami@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user1->save();

        $user2 = new User([
            'name' => 'Shahmir Khan Jadoon',
            'email' => 'shahmirkj@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user2->save();

        $user3 = new User([
            'name' => 'Syed Talha Masood',
            'email' => 'talha@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user3->save();

        $user4 = new User([
            'name' => 'Harris Khan',
            'email' => 'harris@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user4->save();

        $user5 = new User([
            'name' => 'Mehrunisa',
            'email' => 'mehrunisa@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user5->save();

        $user6 = new User([
            'name' => 'Rizwan Khan',
            'email' => 'rizwan@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user6->save();

        $user7 = new User([
            'name' => 'Shamshad Bano',
            'email' => 'shamshad@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user7->save();

        $user8 = new User([
            'name' => 'Akbar Khan',
            'email' => 'akbar@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user8->save();

        $user9 = new User([
            'name' => 'Ahmed Nabi',
            'email' => 'ahmed@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user9->save();

        $user10 = new User([
            'name' => 'Nabeel Suleman',
            'email' => 'nabeel@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user10->save();

        $user11 = new User([
            'name' => 'Farhan Khan',
            'email' => 'farhan@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user11->save();

        $user12 = new User([
            'name' => 'Sudais Masood',
            'email' => 'sudais@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user12->save();

        $user13 = new User([
            'name' => 'Mumraiz Nuqashband',
            'email' => 'mumraiz@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user13->save();

        // Assign super-admin role to both users
        $user1->assignRole('super-admin');
        $user2->assignRole('super-admin');
        $user3->assignRole('admin');
        $user4->assignRole('admin');
        $user5->assignRole('user');
        $user6->assignRole('user');
        $user7->assignRole('user');
        $user8->assignRole('user');
        $user9->assignRole('user');
        $user10->assignRole('user');
        $user11->assignRole('user');
        $user12->assignRole('user');
        $user13->assignRole('user');
    }
}
