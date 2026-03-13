<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestEnterpriseUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'enterprise@test.com'],
            [
                'name' => 'Test Enterprise',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $team = $user->currentTeam ?? Team::forceCreate([
            'user_id' => $user->id,
            'name' => 'Test Enterprise Team',
            'slug' => 'test-enterprise-team',
            'personal_team' => true,
            'onboarded_at' => now(),
        ]);

        if (! $user->current_team_id) {
            $user->update(['current_team_id' => $team->id]);
        }

        if (! DB::table('team_user')->where('team_id', $team->id)->where('user_id', $user->id)->exists()) {
            DB::table('team_user')->insert([
                'team_id' => $team->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $team->update(['onboarded_at' => now()]);

        // Use a known fake price ID — must match what PlanLimiter checks via config
        $priceId = config('dmarcwatch.stripe.prices.enterprise_monthly', 'price_fake_enterprise');

        $team->subscriptions->each(function ($sub) {
            $sub->items()->delete();
            $sub->delete();
        });

        $subId = DB::table('subscriptions')->insertGetId([
            'team_id' => $team->id,
            'type' => 'default',
            'stripe_id' => 'sub_fake_enterprise_test',
            'stripe_status' => 'active',
            'stripe_price' => $priceId,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subscription_items')->insert([
            'subscription_id' => $subId,
            'stripe_id' => 'si_fake_enterprise_test',
            'stripe_product' => 'prod_fake_enterprise',
            'stripe_price' => $priceId,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
