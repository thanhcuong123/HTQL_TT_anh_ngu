  <!-- Topbar Start -->
  <div class="container-fluid bg-dark">
      <div class="row py-2 px-lg-5">
          <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
              <div class="d-inline-flex align-items-center text-white">
                  <small><i class="fa fa-phone-alt mr-2"></i>0702892014</small>
                  <small class="px-3">|</small>river@gmail.com</small>
              </div>
          </div>
          <div class="col-lg-6 text-center text-lg-right">
              <div class="d-inline-flex align-items-center">
                  <a class="text-white px-2" href="">
                      <i class="fab fa-facebook-f"></i>
                  </a>
                  <a class="text-white px-2" href="">
                      <i class="fab fa-twitter"></i>
                  </a>
                  <a class="text-white px-2" href="">
                      <i class="fab fa-linkedin-in"></i>
                  </a>
                  <a class="text-white px-2" href="">
                      <i class="fab fa-instagram"></i>
                  </a>
                  <a class="text-white pl-2" href="">
                      <i class="fab fa-youtube"></i>
                  </a>
              </div>
          </div>
      </div>
  </div>
  <!-- Topbar End -->


  <!-- Navbar Start -->
  <div class="container-fluid p-0">
      <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
          <a href="{{ route('trangchu') }}" class="navbar-brand ml-lg-3">
              <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-book-reader mr-3"></i>River</h1>
          </a>
          <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
              <div class="navbar-nav mx-auto py-0">
                  {{-- Trang chủ link --}}
                  <a href="{{ route('trangchu') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'trangchu' ? 'active' : '' }}">TRANG CHỦ</a>

                  {{-- Giới thiệu link --}}
                  <a href="{{ route('gioithieu') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'gioithieu' ? 'active' : '' }}">GIỚI THIỆU</a>

                  {{-- Các khóa học link --}}
                  <a href="{{ route('courses') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'courses' ? 'active' : '' }}">KHÓA HỌC</a>

                  {{-- Contact link --}}
                  {{-- Giả sử tên route của trang liên hệ là 'contact' --}}
                  <a href="{{ route('contact') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}">LIÊN HỆ</a>
              </div>

              {{-- Phần xử lý đăng nhập/thông tin người dùng --}}
              @guest {{-- Nếu người dùng chưa đăng nhập --}}
              <a href="{{ route('login') }}" class="btn btn-primary py-2 px-4 d-none d-lg-block">HỆ THỐNG QUẢN LÝ</a>
              @else {{-- Nếu người dùng đã đăng nhập --}}
              <div class="nav-item dropdown d-none d-lg-block">
                  <a href="#" class="nav-link dropdown-toggle text-primary" data-toggle="dropdown" style="padding: .5rem 1rem;">
                      @if(Auth::user()->avatar)
                      <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                      @else
                      <img src="{{ asset('storage/avata.png') }}" alt="Avatar mặc định" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                      @endif

                      @php
                      $tenHienThi = Auth::user()->name;
                      if (Auth::user()->giaovien) {
                      $tenHienThi = Auth::user()->giaovien->ten ?? $tenHienThi;
                      } elseif (Auth::user()->hocvien) {
                      $tenHienThi = Auth::user()->hocvien->ten?? $tenHienThi;
                      }
                      @endphp
                      <span>{{ $tenHienThi }}</span>
                  </a>
                  <div class="dropdown-menu m-0 dropdown-menu-right">
                      <a href="" class="dropdown-item">Hồ sơ của bạn</a>
                      <div class="dropdown-divider"></div>

                      @if(Auth::user()->role === 'admin')
                      <a href="{{ route('dashboard') }}" class="dropdown-item">Hệ thống quản lý </a>
                      @elseif(Auth::user()->role === 'giaovien')
                      <a href="" class="dropdown-item">Quản lý Giáo viên</a>
                      @elseif(Auth::user()->role === 'nhanvien')
                      <a href="{{ route('staff.lophoc') }}" class="dropdown-item">Nhân viên</a>
                      @elseif(Auth::user()->role === 'hocvien')
                      <a href="" class="dropdown-item">Trang Học viên</a>
                      @endif
                      <div class="dropdown-divider"></div>

                      <a href="{{ route('logout') }}" class="dropdown-item"
                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          Đăng xuất
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </div>
              </div>
              @endguest
          </div>
      </nav>
  </div>
  <!-- Navbar End -->