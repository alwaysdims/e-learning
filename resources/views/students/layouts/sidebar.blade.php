<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <ul>
        <li>
            <a href="{{ route('student.dashboard') }}"
                class="side-menu {{ request()->routeIs('student.dashboard') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">
                    Dashboard
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('student.materials') }}"
                class="side-menu {{ request()->routeIs('student.materials') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="book-open"></i>
                </div>
                <div class="side-menu__title">
                    Materials
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('student.assignments') }}"
                class="side-menu {{ request()->routeIs('student.assignments') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">
                    Assignments
                </div>
            </a>
        </li>

        {{-- Discussion Forums (tidak diubah) --}}
        <li>
            <a href="{{ route('discussionForums.index') }}" class="side-menu">
                <div class="side-menu__icon">
                    <i data-lucide="message-square"></i>
                </div>
                <div class="side-menu__title">
                    Discussion Forums
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('student.schedules') }}"
                class="side-menu {{ request()->routeIs('student.schedules') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="side-menu__title">
                    Schedules
                </div>
            </a>
        </li>

        <li>
            <a href="{{ route('student.announcements') }}"
                class="side-menu {{ request()->routeIs('student.announcements') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">
                    Announcements
                </div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
