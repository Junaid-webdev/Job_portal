<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition()
{
    return [
        'name' => $this->faker->randomElement([
            'Web Developer',
            'Software Engineer',
            'Graphic Designer',
            'Data Analyst',
            'UI/UX Designer',
            'Digital Marketer',
            'Content Writer',
            'Project Manager',
            'Backend Developer',
            'Frontend Developer'
        ]),
        'status' => 1
    ];
}

}
