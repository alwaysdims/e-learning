<!DOCTYPE html>
<!--
Template Name: Enigma - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="{{ asset('enigma/compiled') }}/dist/images/logoSkandaGoV4.png" class="logo__image w-100 h-[1000px] mt-2"
        rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Enigma admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Enigma Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>SkandaGo | Student {{ $title }}</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('enigma/compiled') }}/dist/css/app.css" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="py-5 md:py-0">
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
                <img alt="Midone - HTML Admin Template" class="w-48 h-12"
                    src="{{ asset('enigma/compiled') }}/dist/images/logoSkandaGoV2.png">
            </a>
            <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="bar-chart-2"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
        </div>
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            <ul class="scrollable__content py-2">

                <li>
                    <a href="{{ route('student.dashboard') }}"
                        class="menu {{ request()->routeIs('student.dashboard') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="home"></i>
                        </div>
                        <div class="menu__title">
                            Dashboard
                        </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('student.materials') }}"
                        class="menu {{ request()->routeIs('student.materials') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="book-open"></i>
                        </div>
                        <div class="menu__title">
                            Materials
                        </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('student.assignments') }}"
                        class="menu {{ request()->routeIs('student.assignments') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div class="menu__title">
                            Assignments
                        </div>
                    </a>
                </li>

                {{-- Discussion Forums (tidak diubah) --}}
                <li>
                    <a href="{{ route('discussionForums.index') }}" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="message-square"></i>
                        </div>
                        <div class="menu__title">
                            Discusion Forums
                        </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('student.schedules') }}"
                        class="menu {{ request()->routeIs('student.schedules') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="calendar"></i>
                        </div>
                        <div class="menu__title">
                            Schedules
                        </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('student.announcements') }}"
                        class="menu {{ request()->routeIs('student.announcements') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div class="menu__title">
                            Announcements
                        </div>
                    </a>
                </li>

            </ul>

        </div>
    </div>
    <!-- END: Mobile Menu -->
    <!-- BEGIN: Top Bar -->
    <div
        class="top-bar-boxed h-[70px] md:h-[65px] z-[51] border-b border-white/[0.08] mt-12 md:mt-0 -mx-3 sm:-mx-8 md:-mx-0 px-3 md:border-b-0 relative md:fixed md:inset-x-0 md:top-0 sm:px-8 md:px-10 md:pt-10 md:bg-gradient-to-b md:from-slate-100 md:to-transparent dark:md:from-darkmode-700">
        <div class="h-full flex items-center">
            <!-- BEGIN: Logo -->
            <a href="" class="logo -intro-x hidden md:flex xl:w-[180px] block">
                <img alt="Logo SkandaGo" class="logo__image  w-[100px] h-75"
                    src="{{ asset('enigma/compiled') }}/dist/images/logoSkandaGoV2.png">
            </a>
            <!-- END: Logo -->
            <!-- BEGIN: Breadcrumb -->
            <nav aria-label="breadcrumb" class="-intro-x h-[45px] mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item"><a href="#">Application</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
            <!-- END: Breadcrumb -->
            <!-- BEGIN: Account Menu -->
            <div class="intro-x dropdown w-8 h-8">
                <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110"
                    role="button" aria-expanded="false" data-tw-toggle="dropdown">
                    <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                        <span class="text-sm font-bold  text-white">
                            {{ Str::upper(Str::limit(Auth::user()->name ?? 'S', 2, '')) }}
                        </span>
                    </div>
                </div>
                <div class="dropdown-menu w-56">
                    <ul
                        class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                        <li class="p-2">
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">
                                {{ Auth::user()->student->nis }}</div>
                        </li>
                        <li>
                            <hr class="dropdown-divider border-white/[0.08]">
                        </li>
                        <li>
                            <a href="" class="dropdown-item hover:bg-white/5"> <i data-lucide="user"
                                    class="w-4 h-4 mr-2"></i> Profile </a>
                        </li>

                        <li>
                            <form id="logout-form" action="{{ route('auth.logout') }}" method="post">
                                @csrf
                                <button type="button" onclick="confirmLogout()"
                                    class="dropdown-item hover:bg-white/5 w-full flex items-center">
                                    <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i>
                                    Logout
                                </button>
                            </form>


                        </li>
                    </ul>
                </div>
            </div>
            <!-- END: Account Menu -->
        </div>
    </div>
    <!-- END: Top Bar -->
    <div class="flex overflow-hidden">
        @include('students.layouts.sidebar')
        <!-- BEGIN: Content -->
        <div class="content">
                @yield('content')
        </div>
        <!-- END: Content -->
    </div>
    @include('students.layouts.footer')
</body>

</html>
