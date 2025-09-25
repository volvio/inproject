<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventRegistration extends Component
{
    use WithPagination;

    public $events = [];
    public $selectedEvent = null;
    public $name;
    public $email;
    public $phone;
    public $participants = [];
    
    public function mount()
    {
        // ⚡ Кэшируем список событий (например, на 10 минут)
        $this->events = Event::orderBy('date', 'asc')->get(['id', 'title', 'capacity', 'date']);

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

    /*protected function rules()
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
    }*/

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
