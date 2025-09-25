<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            'Покататься на байдарках',
            'Участвовать в игре Кеш Флоу',
            'Пеший поход по лесам',
            'Мастер-класс по керамике',
            'Кулинарный воркшоп',
            'Йога на закате',
            'Киноночь под открытым небом',
            'Велопробег вокруг города',
            'Игра в страйкбол',
            'Занятие по ораторскому искусству',
        ];

        foreach ($events as $i => $title) {
            Event::create([
                'title' => $title,
                'date' => Carbon::now()->addDays(rand(1, 30)),
                'capacity' => rand(10, 50),
            ]);
        }
    }
}

