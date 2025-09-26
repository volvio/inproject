<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'date' => $this->faker->dateTimeBetween('+1 day', '+6 months'),
            'capacity' => $this->faker->numberBetween(10, 200),
        ];
    }

    /**
     * Состояние для прошедших мероприятий
     */
    public function past(): self
    {
        return $this->state(fn () => [
            'date' => $this->faker->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }

    /**
     * Состояние для будущих мероприятий
     */
    public function upcoming(): self
    {
        return $this->state(fn () => [
            'date' => $this->faker->dateTimeBetween('+1 day', '+6 months'),
        ]);
    }
}
