<?php

namespace Database\Factories;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Applicant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'onboarding_percentage' => $this->faker->numberBetween(0, 900),
            'count_applications' => $this->faker->numberBetween(0, 220),
            'count_accepted_applications' => $this->faker->numberBetween(0, 50),
            'created_at' => $this->faker->date
        ];
    }
}
