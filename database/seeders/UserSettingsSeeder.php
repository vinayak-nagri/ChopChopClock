<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user)
        {
            if(UserSetting::where('user_id',$user->id)->exists()) continue;
            UserSetting::create([
                'user_id' => $user->id,
                'work_minutes' => 25,
                'short_break_minutes' => 5,
                'long_break_minutes' => 15,
                'notifications_enabled' => true,
                'timezone' => 'UTC',
            ]);
        }
    }
}
