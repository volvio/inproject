<?php

namespace Tests\Feature;

use App\Livewire\EventList;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_upcoming_events_by_default()
    {
        Event::factory()->past()->count(2)->create();
        $futureEvents = Event::factory()->upcoming()->count(3)->create();

        Livewire::test(EventList::class)
            ->assertSee($futureEvents->first()->title)
            ->assertDontSee(Event::first()->title); // прошедшее не показывается
    }

    /** @test */
    public function it_can_filter_past_events()
    {
        $pastEvent = Event::factory()->past()->create();

        Livewire::test(EventList::class)
            ->set('filter', 'past')
            ->assertSee($pastEvent->title);
    }

    /** @test */
    public function it_can_search_events_by_title()
    {
        Event::factory()->create(['title' => 'Laravel Conf']);
        Event::factory()->create(['title' => 'Vue Meetup']);

        Livewire::test(EventList::class)
            ->set('search', 'Laravel')
            ->assertSee('Laravel Conf')
            ->assertDontSee('Vue Meetup');
    }

    /** @test */
    public function it_can_sort_events_by_date()
    {
        $early = Event::factory()->create(['date' => now()->addDays(1)]);
        $late = Event::factory()->create(['date' => now()->addDays(10)]);

        Livewire::test(EventList::class)
            ->set('sortDirection', 'desc')
            ->assertSeeInOrder([$late->title, $early->title]);
    }
}
