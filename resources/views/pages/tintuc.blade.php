@extends('users_layout')
@section('main-content')
<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .article-img {
        transition: transform 0.3s ease;
    }

    .card:hover .article-img {
        transform: scale(1.05);
    }
</style>

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title mb-5 text-center">
                    <h6 class="text-secondary text-uppercase pb-2">Tin Tức</h6>
                    <h1 class="display-6">Cập nhật mới nhất từ trung tâm</h1>
                </div>

                <div class="row">
                    @if (isset($newsArticles) && count($newsArticles) > 0)
                    @foreach ($newsArticles as $article)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border-0 transition-hover">
                            <img src="{{ $article->image_url ?? 'https://placehold.co/600x400/E0F2F7/2C3E50?text=No+Image' }}"
                                class="card-img-top article-img"
                                alt="{{ $article->title }}"
                                style="height: 180px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($article->description, 300) }}</p>
                                <div class="text-muted small mb-2">
                                    <i class="fa fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($article->published_at)->format('d/m/Y') }}
                                    <br>
                                    <i class="fa fa-user mr-1"></i> {{ $article->tacgia ?? 'Admin' }}
                                </div>
                                <a href="{{ route('tintuc.show',$article->id)}}" class="btn btn-sm btn-primary mt-auto align-self-start">Đọc thêm</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-12 text-center">
                        <p>Không có tin tức nào để hiển thị lúc này.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection