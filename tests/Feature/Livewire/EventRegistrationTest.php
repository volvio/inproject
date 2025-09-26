<?php

namespace Tests\Feature;

use App\Livewire\EventRegistration;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_user_successfully()
    {
        $event = Event::factory()->create(['capacity' => 10]);

        Livewire::test(EventRegistration::class, ['event' => $event->id])
            ->set('selectedEvent', $event->id)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->call('submit')
            ->assertSeeText('Регистрация прошла успешно!');

    }

    /** @test */
    public function it_does_not_allow_duplicate_email()
    {
        $event = Event::factory()->create(['capacity' => 10]);
        Registration::factory()->create([
            'event_id' => $event->id,
            'email' => 'john@example.com',
        ]);

        Livewire::test(EventRegistration::class)
            ->set('selectedEvent', $event->id)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->call('submit')
            ->assertHasErrors(['email']);
    }

    /** @test */
    public function it_fails_if_capacity_is_exceeded()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        Registration::factory()->create([
            'event_id' => $event->id,
            'email' => 'existing@example.com',
        ]);

        Livewire::test(EventRegistration::class)
            ->set('selectedEvent', $event->id)
            ->set('name', 'New User')
            ->set('email', 'new@example.com')
            ->call('submit')
            ->assertSeeText('Превышен лимит участников на мероприятие.');
    }

    /** @test */
    public function it_registers_multiple_participants()
    {
        $event = Event::factory()->create(['capacity' => 5]);

        Livewire::test(EventRegistration::class)
            ->set('selectedEvent', $event->id)
            ->set('name', 'Main User')
            ->set('email', 'main@example.com')
            ->set('participants', [
                ['name' => 'Alice', 'email' => 'alice@example.com'],
                ['name' => 'Bob', 'email' => 'bob@example.com'],
            ])
            ->call('submit')
            ->assertSeeText('Регистрация прошла успешно!');

        $this->assertCount(3, Registration::where('event_id', $event->id)->get());
    }
}
