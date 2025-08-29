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
  <style>
      /* Ẩn dropdown ban đầu */
      .nav-item.dropdown .dropdown-menu {
          display: block;
          opacity: 0;
          visibility: hidden;
          transform: translateY(10px);
          transition: all 0.3s ease;
          margin-top: 0;
          /* bỏ khoảng trống */
          border-radius: 8px;
          box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
          padding: 10px 0;
      }

      /* Khi hover thì mượt mà hiện ra */
      .nav-item.dropdown:hover .dropdown-menu {
          opacity: 1;
          visibility: visible;
          transform: translateY(0);
      }

      /* Style item bên trong dropdown */
      .dropdown-menu .dropdown-item {
          padding: 10px 20px;
          transition: background 0.2s ease, padding-left 0.2s ease;
      }

      .dropdown-menu .dropdown-item:hover {
          background-color: #f0f8ff;
          padding-left: 25px;
      }
  </style>

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
                  <!-- <a href="{{ route('courses') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'courses' ? 'active' : '' }}">KHÓA HỌC</a> -->


                  <!-- In pages/layout/hearder.blade.php (or your header view) -->
                  <li class="nav-item dropdown">
                      <a href="#" class="nav-link">KHÓA HỌC</a>
                      <div class="dropdown-menu">
                          @foreach ($khoahocss as $khoaHoc)
                          <a class="dropdown-item"
                              href="{{ route('lop-hoc.byKhoaHoc', ['khoaHocId' => $khoaHoc->khoahoc_id, 'trinhDoId' => $khoaHoc->trinhdo_id]) }}">
                              Khóa {{ $khoaHoc->khoahoc_ten }} - Trình độ {{ $khoaHoc->trinhdo_ten }}
                          </a>
                          @endforeach
                      </div>
                  </li>



                  {{-- Contact link --}}
                  {{-- Giả sử tên route của trang liên hệ là 'contact' --}}
                  <a href="{{ route('user.tintuc') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'user.tintuc' ? 'active' : '' }}">TIN TỨC</a>
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
                      <!-- <a href="" class="dropdown-item">Hồ sơ của bạn</a> -->
                      <!-- <div class="dropdown-divider"></div> -->

                      @if(Auth::user()->role === 'chutt')
                      <a href="{{ route('dashboard') }}" class="dropdown-item">Hệ thống quản lý </a>
                      @elseif(Auth::user()->role === 'giaovien')
                      <a href="{{ route('teacher.dashboard' ) }}" class="dropdown-item">Quản lý Giáo viên</a>
                      @elseif(Auth::user()->role === 'nhanvien')
                      <a href="{{ route('staff.dashboard') }}" class="dropdown-item">Nhân viên</a>
                      @elseif(Auth::user()->role === 'hocvien')
                      <a href="{{ route('student.dashboard') }}" class="dropdown-item">Trang Học viên</a>
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
  <script>
      //   document.getElementById('selectKhoaHoc').addEventListener('change', function() {
      //       let khoaHocId = this.value;
      //       if (khoaHocId) {
      //           window.location.href = '/lop-hoc/khoa-hoc/' + khoaHocId;
      //       }
      //   });
      document.querySelector('.nav-item.dropdown').addEventListener('mouseenter', function() {
          let menu = this.querySelector('.dropdown-menu');
          if (!menu.dataset.loaded) {
              fetch('/api/khoa-hoc-list')
                  .then(res => res.json())
                  .then(data => {
                      menu.innerHTML = '';
                      data.forEach(item => {
                          menu.innerHTML += `
                        <a class="dropdown-item" href="/lop-hoc/${item.id}">
                            Khóa ${item.ten} - Trình độ ${item.trinhdo}
                        </a>
                    `;
                      });
                      menu.dataset.loaded = true;
                  });
          }
      });
  </script>
  <!-- Navbar End -->