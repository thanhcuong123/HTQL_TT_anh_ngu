<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">

                <li class="sidebar-item {{ request()->is('/') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('khoahoc*') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('khoahoc.index') }}" aria-expanded="false">
                        <i class="mdi mdi-chart-bar"></i><span class="hide-menu">Khóa học</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('lophoc.*') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('lophoc.index') }}" aria-expanded="false">
                        <i class="mdi mdi-chart-bubble"></i><span class="hide-menu">Lớp học</span>
                    </a>
                </li>



                <li class="sidebar-item {{ request()->is('hocvien*') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('hocvien.index') }}" aria-expanded="false">
                        <i class="mdi mdi-border-inside"></i><span class="hide-menu">Học viên</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('giaovien*') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('giaovien.index') }}" aria-expanded="false">
                        <i class="mdi mdi-blur-linear"></i><span class="hide-menu">Giáo viên</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('trinhdo*') || request()->is('kynang*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="{{ request()->is('trinhdo*') || request()->is('kynang*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-receipt"></i><span class="hide-menu">Danh mục</span>
                    </a>
                    <ul aria-expanded="{{ request()->is('trinhdo*') || request()->is('kynang*') ? 'true' : 'false' }}" class="collapse first-level {{ request()->is('trinhdo*') || request()->is('kynang*') ? 'in' : '' }}">
                        <li class="sidebar-item {{ request()->is('trinhdo*') ? 'active' : '' }}">
                            <a href="{{ route('trinhdo.index') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i><span class="hide-menu"> Trình độ </span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('kynang*') ? 'active' : '' }}">
                            <a href="{{ route('kynang.index') }}" class="sidebar-link">
                                <i class="mdi mdi-note-plus"></i><span class="hide-menu"> Kỹ năng </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('hocphi*') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('hocphi.index') }}" aria-expanded="false">
                        <i class="mdi mdi-relative-scale"></i><span class="hide-menu">Quản lý thu học phí</span>
                    </a>
                </li>
                <!-- 
                <li class="sidebar-item {{ request()->is('icon*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="{{ request()->is('icon*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-face"></i><span class="hide-menu">Icons</span>
                    </a>
                    <ul aria-expanded="{{ request()->is('icon*') ? 'true' : 'false' }}" class="collapse first-level {{ request()->is('icon*') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="icon-material.html" class="sidebar-link">
                                <i class="mdi mdi-emoticon"></i><span class="hide-menu"> Material Icons </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="icon-fontawesome.html" class="sidebar-link">
                                <i class="mdi mdi-emoticon-cool"></i><span class="hide-menu"> Font Awesome Icons </span>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <li class="sidebar-item {{ request()->is('tuvan*') ? 'active' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('tuvan') }}" aria-expanded="false">
                        <i class="mdi mdi-pencil"></i><span class="hide-menu">Quản lý tư vấn</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('cahoc*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="{{ request()->is('cahoc*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-move-resize-variant"></i><span class="hide-menu">Thời khóa biểu</span>
                    </a>
                    <ul aria-expanded="{{ request()->is('cahoc*') ? 'true' : 'false' }}" class="collapse first-level {{ request()->is('cahoc*') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="{{ route('general.calendar') }}" class="sidebar-link">
                                <i class="mdi mdi-view-dashboard"></i><span class="hide-menu"> TKB </span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('cahoc*') ? 'active' : '' }}">
                            <a href="{{ route('cahoc.index') }}" class="sidebar-link">
                                <i class="mdi mdi-multiplication-box"></i><span class="hide-menu">Ca học</span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-item">
                            <a href="pages-calendar.html" class="sidebar-link">
                                <i class="mdi mdi-calendar-check"></i><span class="hide-menu"> Calendar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="pages-invoice.html" class="sidebar-link">
                                <i class="mdi mdi-bulletin-board"></i><span class="hide-menu"> Invoice </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="pages-chat.html" class="sidebar-link">
                                <i class="mdi mdi-message-outline"></i><span class="hide-menu"> Chat Option </span>
                            </a>
                        </li> -->
                    </ul>
                </li>
                <!-- 
                <li class="sidebar-item {{ request()->is('authentication*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="{{ request()->is('authentication*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-account-key"></i><span class="hide-menu">Authentication</span>
                    </a>
                    <ul aria-expanded="{{ request()->is('authentication*') ? 'true' : 'false' }}" class="collapse first-level {{ request()->is('authentication*') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="authentication-login.html" class="sidebar-link">
                                <i class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Login </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="authentication-register.html" class="sidebar-link">
                                <i class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Register </span>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <li class="sidebar-item {{ request()->is('coso*') || request()->is('phonghoc*') ? 'active' : '' }}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="{{ request()->is('coso*') || request()->is('phonghoc*') ? 'true' : 'false' }}">
                        <i class="mdi mdi-alert"></i><span class="hide-menu">Quản lí cơ sở</span>
                    </a>
                    <ul aria-expanded="{{ request()->is('coso*') || request()->is('phonghoc*') ? 'true' : 'false' }}" class="collapse first-level {{ request()->is('coso*') || request()->is('phonghoc*') ? 'in' : '' }}">
                        <li class="sidebar-item {{ request()->is('coso*') ? 'active' : '' }}">
                            <a href="{{ route('coso.index') }}" class="sidebar-link">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Cơ sở </span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->is('phonghoc*') ? 'active' : '' }}">
                            <a href="{{ route('phonghoc.index') }}" class="sidebar-link">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Phòng học </span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-item">
                            <a href="error-500.html" class="sidebar-link">
                                <i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Error 500 </span>
                            </a>
                        </li> -->
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>