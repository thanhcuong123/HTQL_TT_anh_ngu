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

                <!-- ✅ Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- ✅ Khóa học -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('khoahoc.*') ? 'active' : '' }}"
                        href="{{ route('khoahoc.index') }}">
                        <i class="mdi mdi-chart-bar"></i><span class="hide-menu">Khóa học</span>
                    </a>
                </li>

                <!-- ✅ Lớp học -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('lophoc.*') ? 'active' : '' }}"
                        href="{{ route('lophoc.index') }}">
                        <i class="mdi mdi-chart-bubble"></i><span class="hide-menu">Lớp học</span>
                    </a>
                </li>

                <!-- ✅ Học viên -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('hocvien.*') ? 'active' : '' }}"
                        href="{{ route('hocvien.index') }}">
                        <i class="mdi mdi-border-inside"></i><span class="hide-menu">Học viên</span>
                    </a>
                </li>

                <!-- ✅ Giáo viên -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('giaovien.*') ? 'active' : '' }}"
                        href="{{ route('giaovien.index') }}">
                        <i class="mdi mdi-blur-linear"></i><span class="hide-menu">Giáo viên</span>
                    </a>
                </li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.nhanvien') }}" aria-expanded="false"><i class="mdi mdi-relative-scale"></i><span class="hide-menu">Nhân viên</span></a></li>

                <!-- ✅ Danh mục CHA - CON -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark
                        {{ request()->routeIs('trinhdo.*') || request()->routeIs('kynang.*') ? 'active' : '' }}"
                        href="javascript:void(0)"
                        aria-expanded="{{ request()->routeIs('trinhdo.*') || request()->routeIs('kynang.*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-receipt"></i><span class="hide-menu">Danh mục</span>
                    </a>
                    <ul aria-expanded="{{ request()->routeIs('trinhdo.*') || request()->routeIs('kynang.*') ? 'true' : 'false' }}"
                        class="collapse first-level {{ request()->routeIs('trinhdo.*') || request()->routeIs('kynang.*') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="{{ route('trinhdo.index') }}"
                                class="sidebar-link {{ request()->routeIs('trinhdo.*') ? 'active' : '' }}">
                                <i class="mdi mdi-note-outline"></i><span class="hide-menu"> Trình độ </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('kynang.index') }}"
                                class="sidebar-link {{ request()->routeIs('kynang.*') ? 'active' : '' }}">
                                <i class="mdi mdi-note-plus"></i><span class="hide-menu"> Kỹ năng </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('dongia.index') }}"
                                class="sidebar-link {{ request()->routeIs('dongia.*') ? 'active' : '' }}">
                                <i class="mdi mdi-note-plus"></i><span class="hide-menu"> Đơn giá học phí </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ✅ Quản lý thu học phí -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('hocphi.*') ? 'active' : '' }}"
                        href="{{ route('hocphi.index') }}">
                        <i class="mdi mdi-relative-scale"></i><span class="hide-menu">Quản lý thu học phí</span>
                    </a>
                </li>

                <!-- ✅ Quản lý tư vấn -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark {{ request()->routeIs('tuvan*') ? 'active' : '' }}"
                        href="{{ route('tuvan') }}">
                        <i class="mdi mdi-pencil"></i><span class="hide-menu">Quản lý tư vấn</span>
                    </a>
                </li>

                <!-- ✅ Thời khóa biểu CHA - CON -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark
                        {{ request()->routeIs('cahoc.*') || request()->routeIs('general.calendar') ? 'active' : '' }}"
                        href="javascript:void(0)"
                        aria-expanded="{{ request()->routeIs('cahoc.*') || request()->routeIs('general.calendar') ? 'true' : 'false' }}">
                        <i class="mdi mdi-move-resize-variant"></i><span class="hide-menu">Thời khóa biểu</span>
                    </a>
                    <ul aria-expanded="{{ request()->routeIs('cahoc.*') || request()->routeIs('general.calendar') ? 'true' : 'false' }}"
                        class="collapse first-level {{ request()->routeIs('cahoc.*') || request()->routeIs('general.calendar') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="{{ route('general.calendar') }}" class="sidebar-link {{ request()->routeIs('general.calendar') ? 'active' : '' }}">
                                <i class="mdi mdi-view-dashboard"></i><span class="hide-menu"> TKB </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('cahoc.index') }}" class="sidebar-link {{ request()->routeIs('cahoc.*') ? 'active' : '' }}">
                                <i class="mdi mdi-multiplication-box"></i><span class="hide-menu"> Ca học </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ✅ Quản lý cơ sở CHA - CON -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark
                        {{ request()->routeIs('coso.*') || request()->routeIs('phonghoc.*') ? 'active' : '' }}"
                        href="javascript:void(0)"
                        aria-expanded="{{ request()->routeIs('coso.*') || request()->routeIs('phonghoc.*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-alert"></i><span class="hide-menu">Quản lý cơ sở</span>
                    </a>
                    <ul aria-expanded="{{ request()->routeIs('coso.*') || request()->routeIs('phonghoc.*') ? 'true' : 'false' }}"
                        class="collapse first-level {{ request()->routeIs('coso.*') || request()->routeIs('phonghoc.*') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="{{ route('coso.index') }}" class="sidebar-link {{ request()->routeIs('coso.*') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Cơ sở </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('phonghoc.index') }}" class="sidebar-link {{ request()->routeIs('phonghoc.*') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Phòng học </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ✅ Báo cáo CHA - CON -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark
                        {{ request()->routeIs('report.*') || request()->routeIs('reports.*') || request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                        href="javascript:void(0)"
                        aria-expanded="{{ request()->routeIs('report.*') || request()->routeIs('reports.*') || request()->routeIs('admin.reports.*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-alert"></i><span class="hide-menu">Báo cáo</span>
                    </a>
                    <ul aria-expanded="{{ request()->routeIs('report.*') || request()->routeIs('reports.*') || request()->routeIs('admin.reports.*') ? 'true' : 'false' }}"
                        class="collapse first-level {{ request()->routeIs('report.*') || request()->routeIs('reports.*') || request()->routeIs('admin.reports.*') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="{{ route('report.class_student') }}" class="sidebar-link {{ request()->routeIs('report.class_student') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Lớp học, học viên </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('reports.paid-students') }}" class="sidebar-link {{ request()->routeIs('reports.paid-students') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Học viên đã đóng HP </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('reports.unpaid-students') }}" class="sidebar-link {{ request()->routeIs('reports.unpaid-students') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Học viên chưa đóng HP</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.reports.revenue') }}" class="sidebar-link {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Doanh thu </span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-item">
                            <a href="{{ route('diemdanh.report') }}" class="sidebar-link {{ request()->routeIs('diemdanh.report') ? 'active' : '' }}">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Điểm danh</span>
                            </a>
                        </li> -->
                    </ul>
                </li>
                <!-- ✅ Quản lý tài khoản CHA - CON -->

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-alert"></i><span class="hide-menu"> Quản lý tài khoản</span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="{{ route('admin.hocvien.accountIndex') }}" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Học viên </span></a></li>
                        <li class="sidebar-item"><a href="{{ route('admin.giaovien.accountIndex') }}" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Giáo viên</span></a></li>
                        <li class="sidebar-item"><a href="{{ route('admin.nhanvien.accountIndex') }}" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Nhân viên</span></a></li>
                        <!-- <li class="sidebar-item"><a href="error-500.html" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Error 500 </span></a></li> -->
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>