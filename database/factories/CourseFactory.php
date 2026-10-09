<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'language' => fake()->randomElement(['Spanish', 'English', 'German', 'Italian', 'French']),
            'level' => fake()->randomElement(['A1', 'A2', 'B1', 'B2', 'C1', 'C2']),
            'schedule' => fake()->randomElement(['Monday 19:00', 'Tuesday 19:00', 'Wednesday 19:00', 'Thursday 19:00', 'Friday 19:00', 'Saturday 10:00']),
            'teacher_id' => User::factory()->teacher(),
        ];
    }
}
