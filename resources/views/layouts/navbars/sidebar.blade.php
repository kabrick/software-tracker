<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">

            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('theme_guide') }}">
                        <i class="ni ni-planet text-blue"></i> Theme Guide
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.index') }}">
                        <i class="ni ni-planet text-blue"></i> View Projects
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
