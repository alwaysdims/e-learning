<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <ul>

        <!-- Dashboard -->
        <li>
            <a href="{{ route('teacher.dashboard') }}"
                class="side-menu {{ request()->routeIs('teacher.dashboard') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">Dashboard</div>
            </a>
        </li>

        <!-- Materials -->
        <li>
            <a href="{{ route('teacher.materials.index') }}"
                class="side-menu {{ request()->routeIs('teacher.materials.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">Materials</div>
            </a>
        </li>

        <!-- Assignments -->
        <li>
            <a href="{{ route('teacher.assignments') }}"
                class="side-menu {{ request()->routeIs('teacher.assignments') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="layers"></i>
                </div>
                <div class="side-menu__title">Assignments</div>
            </a>
        </li>

        <!-- Discussion Forum (BELUM ADA ROUTE) -->
        <li>
            <a href="{{ route('discussionForums.index') }}" class="side-menu opacity-60 cursor-not-allowed">
                <div class="side-menu__icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="side-menu__title">Discussion Forums</div>
            </a>
        </li>

        <!-- Schedules -->
        <li>
            <a href="{{ route('teacher.schedules') }}" class="side-menu {{ request()->routeIs('teacher.schedules') ? 'side-menu--active' : '' }}">

                <div class="side-menu__icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="side-menu__title">Schedules</div>
            </a>
        </li>
        <!-- announcement -->
        <li>
            <a href="{{ route('teacher.announcements') }}" class="side-menu {{ request()->routeIs('teacher.announcements') ? 'side-menu--active' : '' }}">

                <div class="side-menu__icon">
                    <i data-lucide="bell"></i>
                </div>
                <div class="side-menu__title">Announcements</div>
            </a>
        </li>

    </ul>
</nav>
<!-- END: Side Menu -->
