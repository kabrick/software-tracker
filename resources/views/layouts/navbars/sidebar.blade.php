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
                    <a class="nav-link active" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                        <i class="ni ni-archive-2 text-blue"></i>
                        <span class="nav-link-text">Archived Items</span>
                    </a>

                    <div class="collapse show" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('projects.view_archived_projects') }}">Projects</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('projects.view_archived_versions') }}">Project Versions</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('project_versions.view_archived_guides') }}">Project Version Guides</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
