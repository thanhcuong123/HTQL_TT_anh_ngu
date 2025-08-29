<aside class="left-sidebar" data-sidebarbg="skin5">
    <style>
        /* Link cha KHÔNG có submenu, active thì tô màu */
        .sidebar-item>a.sidebar-link.active:not(.has-arrow) {
            background-color: #27a9e3;
            color: #fff !important;
        }

        .sidebar-item>a.sidebar-link.active:not(.has-arrow) i {
            color: #fff !important;
        }

        /* Link con trong submenu active vẫn tô */
        .sidebar-item ul .sidebar-link.active {
            background-color: #27a9e3;
            color: #fff !important;
        }

        .sidebar-item ul .sidebar-link.active i {
            color: #fff !important;
        }

        /* Cha có has-arrow active thì KHÔNG tô */
        .sidebar-item>a.has-arrow.active {
            background-color: transparent !important;
            color: inherit !important;
        }

        .sidebar-item>a.has-arrow.active i {
            color: inherit !important;
        }
    </style>

    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"
                        href="{{ route('staff.dashboard') }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.lophoc*') ? 'active' : '' }}"
                        href="{{ route('staff.lophoc') }}">
                        <i class="mdi mdi-chart-bar"></i>
                        <span class="hide-menu">Lớp học</span>
                    </a>
                </li>


                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.hocvien') ? 'active' : '' }}"
                        href="{{ route('staff.hocvien') }}">
                        <i class="mdi mdi-chart-bubble"></i>
                        <span class="hide-menu">Học viên</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.tuvan') ? 'active' : '' }}"
                        href="{{ route('staff.tuvan') }}">
                        <i class="mdi mdi-border-inside"></i>
                        <span class="hide-menu">Tư vấn</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.general.calendar') ? 'active' : '' }}"
                        href="{{ route('staff.general.calendar') }}">
                        <i class="mdi mdi-blur-linear"></i>
                        <span class="hide-menu">Thời khóa biểu</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.registrations.create') ? 'active' : '' }}"
                        href="{{ route('staff.registrations.create') }}">
                        <i class="mdi mdi-pencil"></i>
                        <span class="hide-menu">Đăng kí học viên</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('staff.hocphi') ? 'active' : '' }}"
                        href="{{ route('staff.hocphi') }}">
                        <i class="mdi mdi-relative-scale"></i>
                        <span class="hide-menu">Quản lý thu học phí</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link {{ request()->routeIs('tintuc') ? 'active' : '' }}"
                        href="{{ route('tintuc') }}">
                        <i class="mdi mdi-relative-scale"></i>
                        <span class="hide-menu">Quản lý tin tức</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>