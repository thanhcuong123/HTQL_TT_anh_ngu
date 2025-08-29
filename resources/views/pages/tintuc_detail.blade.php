@extends('users_layout')
@section('main-content')

<div class="container py-5">
    <div class="row">
        <!-- Cột trái: Bài viết chính -->
        <div class="col-lg-8 mb-5">
            <!-- Tiêu đề -->
            <h1 class="display-5 fw-bold">{{ $news->tieude }}</h1>
            <!-- Thông tin phụ -->
            <div class="text-muted small mb-3">
                <i class="fa fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($news->ngaydang)->format('d/m/Y') }}
                &nbsp; | &nbsp;
                <i class="fa fa-user me-1"></i> {{ $news->tacgia?->ten ?? 'Admin' }}
            </div>

            <!-- Hình ảnh -->
            @if ($news->hinhanh)
            <div class="mb-4">
                <img src="{{ $news->hinhanh }}" alt="Hình ảnh tin tức" class="img-fluid rounded shadow-sm"
                    style="max-height: 450px; object-fit: cover; width: 100%;">
            </div>
            @endif

            <!-- Nội dung -->
            <div class="news-content mb-4 fs-6 lh-lg">
                {!! $news->noidung !!}
            </div>

            <!-- Nút quay lại -->
            <!-- <a href="{{ route('tintuc') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại danh sách
            </a> -->
        </div>

        <!-- Cột phải: Tin liên quan -->
        <div class="col-lg-4">
            <h4 class="mb-4">Tin tức liên quan</h4>

            @forelse ($relatedNews as $item)
            <a href="{{ route('tintuc.show', $item->id) }}" class="text-decoration-none text-dark">
                <div class="card mb-3 border-0 shadow-sm hover-shadow transition-all" style="transition: all 0.3s;">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="{{ $item->hinhanh ?? 'https://placehold.co/150x100' }}" alt="{{ $item->tieude }}"
                                class="img-fluid rounded-start h-100 object-fit-cover"
                                style="object-fit: cover; height: 100%;">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">{{ Str::limit($item->tieude, 50) }}</h6>
                                <small class="text-muted">
                                    <i class="fa fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($item->ngaydang)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <p class="text-muted">Không có tin liên quan.</p>
            @endforelse
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-3px);
    }
</style>

@endsection