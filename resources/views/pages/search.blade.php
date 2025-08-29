@extends('users_layout') {{-- hoặc layout chính của bạn --}}
@section('title', 'Tìm kiếm lớp học')

@section('main-content')
<div class="container mt-4">
    <h3 class="mb-3">Tìm kiếm lớp học</h3>

    {{-- Form tìm kiếm --}}


    {{-- Kết quả tìm kiếm --}}
    @if($lopHocResults->isNotEmpty())
    <div class="row">
        @foreach($lopHocResults as $lop)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $lop->tenlophoc }}</h5>
                    <p class="card-text mb-1"><strong>Mã lớp:</strong> {{ $lop->malophoc }}</p>
                    <p class="card-text mb-1"><strong>Khóa học:</strong> {{ $lop->khoaHoc->ma ?? '' }}</p>
                    <p class="card-text mb-1"><strong>Trình độ:</strong> {{ $lop->trinhDo->ten ?? '' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p>Không tìm thấy lớp học nào phù hợp.</p>
    @endif
</div>
@endsection