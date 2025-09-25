<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'upcoming'; // upcoming | past | all
    public string $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'upcoming'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function sortByDate()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        $query = Event::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filter === 'upcoming', fn($q) => $q->where('date', '>=', now()))
            ->when($this->filter === 'past', fn($q) => $q->where('date', '<', now()))
            ->withCount('registrations')
            ->orderBy('date', $this->sortDirection);

        return view('livewire.event-list', [
            'events' => $query->paginate(10),
        ]);
    }
}
