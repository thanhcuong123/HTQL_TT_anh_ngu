@extends('users_layout')

@section('main-content')

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row mx-0 justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center position-relative mb-5">
                    <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Các lớp học trong khóa học</h6>
                    <h1 class="display-4">Danh sách lớp học </h1>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse ($lopHocs as $lop)
            <div class="col-lg-4 col-md-6 pb-4">
                <a class="courses-list-item position-relative d-block overflow-hidden mb-2" href="{{ route('class.show', $lop->id) }}">

                    {{-- Ảnh lớp học --}}
                    @if ($lop->hinhanh)
                    <img class="img-fluid" src="{{ asset('storage/' . $lop->hinhanh) }}" alt="{{ $lop->ten }}">
                    @else
                    <img class="img-fluid" src="{{ asset('img/default-class.jpg') }}" alt="Không có ảnh">
                    @endif

                    {{-- Thông tin lớp học --}}
                    <div class="courses-text">
                        <h4 class="text-center text-white px-3">
                            {{ $lop->tenlophoc }}
                            @if($lop->trinhDo)
                            <!-- - {{ $lop->trinhDo->ten }} -->
                            @endif
                        </h4>

                        <div class="border-top w-100 mt-3">
                            <div class="d-flex justify-content-between p-4">
                                <span class="text-white"><i class="fa fa-user mr-2"></i>{{ $lop->giaoVien->ten ?? 'Đang cập nhật' }}</span>
                                <span class="text-white"><i class="fa fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($lop->ngay_bat_dau)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                </a>
                <p class="mt-2 text-muted small">
                    {{ Str::limit($lop->mota, 80) ?? 'Chưa có mô tả cho lớp học này.' }}
                </p>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="lead">Bạn chưa tham gia lớp học nào!</p>
            </div>
            @endforelse

            {{-- Phân trang --}}
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-lg justify-content-center mb-0">
                        {{ $lopHocs->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- CSS cho hiệu ứng đẹp hơn --}}
<style>
    .courses-list-item {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .courses-list-item img {
        width: 100%;
        height: 350px;
        object-fit: cover;
    }

    .courses-text {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        padding-top: 15px;
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
        visibility: hidden;
    }

    .courses-list-item:hover .courses-text {
        opacity: 1;
        visibility: visible;
    }

    .courses-list-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>

@endsection