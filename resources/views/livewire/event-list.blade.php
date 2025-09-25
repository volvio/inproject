<div class="container py-4">
    <h1 class="mb-4">📅 Список мероприятий</h1>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" wire:model.live="search" class="form-control" placeholder="🔍 Поиск по названию...">
        </div>
        <div class="col-md-4">
            <select wire:model.live="filter" class="form-select">
                <option value="upcoming">Предстоящие</option>
                <option value="past">Прошедшие</option>
                <option value="all">Все</option>
            </select>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-outline-primary" wire:click="sortByDate">
                Сортировать по дате 
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
            </button>
        </div>
    </div>

    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>Название</th>
                <th>Дата</th>
                <th>Количество участников</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->date->format('d.m.Y H:i') }}</td>
                <td>{{ $event->registrations_count }} / {{ $event->capacity }}</td>
                <td>
                    <a href="{{ route('events.register', $event->id) }}" class="btn btn-sm btn-success">Регистрация</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Нет мероприятий</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div>
        {{ $events->links() }}
    </div>
</div>
