<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Domain>
 */
class DomainFactory extends Factory
{
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->unique()->domainName(),
            'is_active' => true,
            'rua_address' => Str::random(8) . '@reports.dmarcwatch.app',
            'dmarc_policy' => 'none',
            'spf_status' => null,
            'dkim_status' => null,
        ];
    }
}
