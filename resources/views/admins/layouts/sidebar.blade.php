<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <ul>
        {{-- Classes --}}
        <li>
            <a href="{{ route('admin.classes.index') }}"
               class="side-menu {{ request()->routeIs('admin.classes.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="layers"></i></div>
                <div class="side-menu__title">Classes</div>
            </a>
        </li>

        {{-- Manage Users --}}
        <li>
            <a href="javascript:;"
               class="side-menu {{ request()->routeIs('admin.user.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="settings"></i></div>
                <div class="side-menu__title">
                    Manage Users
                    <div class="side-menu__sub-icon"><i data-lucide="chevron-down"></i></div>
                </div>
            </a>
            <ul class="{{ request()->routeIs('admin.user.*') ? 'side-menu__sub-open' : 'hidden' }}">
                <li>
                    <a href="{{ route('admin.user.admin.index') }}"
                       class="side-menu {{ request()->routeIs('admin.user.admin.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="user-plus"></i></div>
                        <div class="side-menu__title">Admins</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.user.teacher.index') }}"
                       class="side-menu {{ request()->routeIs('admin.user.teacher.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="user-plus"></i></div>
                        <div class="side-menu__title">Teachers</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.user.student.index') }}"
                       class="side-menu {{ request()->routeIs('admin.user.student.*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-lucide="user-plus"></i></div>
                        <div class="side-menu__title">Students</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Subjects --}}
        <li>
            <a href="{{ route('admin.subjects.index') }}"
               class="side-menu {{ request()->routeIs('admin.subjects.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="columns"></i></div>
                <div class="side-menu__title">Subjects</div>
            </a>
        </li>

        {{-- Achievements --}}
        <li>
            <a href="{{ route('admin.achievements.index') }}"
               class="side-menu {{ request()->routeIs('admin.achievements.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="award"></i></div>
                <div class="side-menu__title">Achievements</div>
            </a>
        </li>

        {{-- Majors --}}
        <li>
            <a href="{{ route('admin.majors.index') }}"
               class="side-menu {{ request()->routeIs('admin.majors.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="activity"></i></div>
                <div class="side-menu__title">Majors</div>
            </a>
        </li>

        {{-- Schedules --}}
        <li>
            <a href="{{ route('admin.schedules.index') }}"
               class="side-menu {{ request()->routeIs('admin.schedules.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="calendar"></i></div>
                <div class="side-menu__title">Schedules</div>
            </a>
        </li>

        {{-- Announcements --}}
        <li>
            <a href="{{ route('admin.announcements.index') }}"
               class="side-menu {{ request()->routeIs('admin.announcements.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"><i data-lucide="file-text"></i></div>
                <div class="side-menu__title">Announcements</div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
