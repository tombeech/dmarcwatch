<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Team;

return new class extends Migration
{
    public function up(): void
    {
        if (User::where('email', 'tom@thsapps.co')->exists()) {
            return;
        }

        $user = User::forceCreate([
            'name' => 'Tom',
            'email' => 'tom@thsapps.co',
            'email_verified_at' => now(),
            'password' => Hash::make('J4m4ican$'),
        ]);

        $team = Team::forceCreate([
            'user_id' => $user->id,
            'name' => "Tom's Team",
            'personal_team' => true,
        ]);

        $user->current_team_id = $team->id;
        $user->save();
    }

    public function down(): void
    {
        $user = User::where('email', 'tom@thsapps.co')->first();

        if ($user) {
            Team::where('user_id', $user->id)->where('personal_team', true)->delete();
            $user->delete();
        }
    }
};
