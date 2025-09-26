<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class EventRegistration extends Component
{
    use WithPagination;

    public $events = [];
    public $selectedEvent = null;
    public $name;
    public $email;
    public $phone;
    public $participants = [];
    private $cacheName = 'events_registration';
    
    public function mount($event = null)
    {
        $events = Cache::remember($this->cacheName, 30, function () {
            return   Event::orderBy('date', 'asc')->get(['id', 'title', 'capacity', 'date']);
        });
        $this->events = $events;
        if ($event) {
            $this->selectedEvent = $event;
        }

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
    
     public function submit()
    {
        $this->validate();
            
        $event = Event::find($this->selectedEvent);

        $totalParticipants = 1 + count($this->participants);
        $currentRegistrations = Registration::where('event_id', $event->id)->count();

        if ($currentRegistrations + $totalParticipants > $event->capacity) {
            session()->flash('error', 'Превышен лимит участников на мероприятие.');
            $this->selectedEvent = $event->id;
            return view('livewire.event-registration');
        }
        DB::beginTransaction();
        // Регистрация основного участника
        try {
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
            DB::commit(); 
        } catch (\Exception $e) {
            DB::rollBack(); // Откат всех операций при ошибке
            session()->flash('error', 'Произошла ошибка: ' . $e->getMessage());
        }

        // 🧹 Очистка кэша списка событий
       // Cache::forget($this->cacheName);

        session()->flash('success', 'Регистрация прошла успешно!');
        $this->reset(['selectedEvent', 'name', 'email', 'phone', 'participants']);
        $this->mount(); // перезагрузить события
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

    public function render()
    {
        return view('livewire.event-registration');
    }
}
