<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="bi bi-house-door"></i>&nbsp;Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('events.list') }}">
                        <i class="bi bi-calendar-event"></i>&nbsp;Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('events.register') }}">
                        <i class="bi bi-pencil-square"></i>&nbsp;Registration
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>