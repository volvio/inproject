<div class="container mt-4">
    <h2 class="mb-4">Регистрация на мероприятие</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form wire:submit.prevent="submit">

        <div class="mb-3">
            <label for="event" class="form-label">Выберите мероприятие</label>
            <select wire:model="selectedEvent" id="event" class="form-select">
                <option value="">-- выберите --</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">
                        {{ $event->title }} ({{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }})
                    </option>
                @endforeach
            </select>
            @error('selectedEvent') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ваше имя</label>
            <input type="text" wire:model="name" class="form-control">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" wire:model="email" class="form-control">
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Телефон (необязательно)</label>
            <input type="text" wire:model="phone" class="form-control">
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <hr>

        <h4>Дополнительные участники</h4>
        @foreach($participants as $index => $participant)
            <div class="border p-3 mb-2">
                <div class="mb-2">
                    <label>Имя</label>
                    <input type="text" wire:model="participants.{{ $index }}.name" class="form-control">
                    @error("participants.$index.name") <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" wire:model="participants.{{ $index }}.email" class="form-control">
                    @error("participants.$index.email") <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <button type="button" class="btn btn-danger" wire:click="removeParticipant({{ $index }})">Удалить</button>
            </div>
        @endforeach

        <button type="button" class="btn btn-outline-primary mb-3" wire:click="addParticipant">
            + Добавить участника
        </button>

        <div>
            <button type="submit" class="btn btn-success">Зарегистрироваться</button>
        </div>
    </form>
</div>
