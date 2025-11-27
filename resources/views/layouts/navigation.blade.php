<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">{{ config('app.name', 'CRM') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('clients.*')) active @endif" href="{{ route('clients.index') }}">Clients</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('projects.*')) active @endif" href="{{ route('projects.index') }}">Projects</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('tasks.*')) active @endif" href="{{ route('tasks.index') }}">Tasks</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('reports.*')) active @endif" href="{{ route('reports.index') }}">Reports</a></li>
                @if(Auth::user()?->isAdmin())
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}">Users</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('activity-logs.*')) active @endif" href="{{ route('activity-logs.index') }}">Activity</a></li>
                @endif
            </ul>
            <form class="d-flex me-3" action="{{ route('search') }}" method="get">
                <input class="form-control form-control-sm me-2" type="search" placeholder="Search" name="q" value="{{ request('q') }}">
                <button class="btn btn-sm btn-primary" type="submit">Go</button>
            </form>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
