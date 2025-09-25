<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EventRegistration extends Component
{
    public $events = [];
    public $selectedEvent = null;
    public $name;
    public $email;
    public $phone;
    public $participants = [];

    public function mount()
    {
        // ⚡ Кэшируем список событий (например, на 10 минут)
        $this->events = Cache::remember('events_list', 600, function () {
            return Event::orderBy('date', 'asc')->get(['id', 'title', 'capacity', 'date']);
        });
    }

    public function addParticipant()
    {
        $this->participants[] = ['name' => '', 'email' => ''];
    }

    public function removeParticipant($index)
    {
        unset($this->participants[$index]);
        $this->participants = array_values($this->participants);
    }

    protected function rules()
    {
        return [
            'selectedEvent' => 'required|exists:events,id',
            'name' => 'required|string|min:3',
            'email' => [
                'required', 'email',
                function ($attribute, $value, $fail) {
                    if (Registration::where('event_id', $this->selectedEvent)->where('email', $value)->exists()) {
                        $fail('Этот email уже зарегистрирован на мероприятие.');
                    }
                }
            ],
            'phone' => 'nullable|string|min:10',
            'participants.*.name' => 'required|string|min:3',
            'participants.*.email' => [
                'required', 'email',
                function ($attribute, $value, $fail) {
                    if (Registration::where('event_id', $this->selectedEvent)->where('email', $value)->exists()) {
                        $fail('Email "' . $value . '" уже зарегистрирован на мероприятие.');
                    }
                }
            ],
        ];
    }

    protected function messages()
    {
        return [
            'selectedEvent.required' => 'Выберите мероприятие.',
            'name.required' => 'Введите имя.',
            'email.required' => 'Введите email.',
            'participants.*.name.required' => 'Введите имя для каждого участника.',
            'participants.*.email.required' => 'Введите email для каждого участника.',
        ];
    }

    public function submit()
    {
        $this->validate();

        DB::transaction(function () {
            $event = Event::lockForUpdate()->find($this->selectedEvent);

            $totalParticipants = 1 + count($this->participants);
            $currentRegistrations = Registration::where('event_id', $event->id)->count();

            if ($currentRegistrations + $totalParticipants > $event->capacity) {
                throw new \Exception('Превышен лимит участников на мероприятие.');
            }

            // Регистрация основного участника
            Registration::create([
                'event_id' => $event->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);

            // Регистрация дополнительных участников
            foreach ($this->participants as $p) {
                Registration::create([
                    'event_id' => $event->id,
                    'name' => $p['name'],
                    'email' => $p['email'],
                    'phone' => null,
                ]);
            }
        });

        // 🧹 Очистка кэша списка событий
        Cache::forget('events_list');

        session()->flash('success', 'Регистрация прошла успешно!');
        $this->reset(['selectedEvent', 'name', 'email', 'phone', 'participants']);
        $this->mount(); // перезагрузить события
    }

    public function render()
    {
        return view('livewire.event-registration');
    }
}
