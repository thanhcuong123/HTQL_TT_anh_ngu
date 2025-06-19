@if ($dslophoc->count() > 0)
@foreach ($dslophoc as $lh)
<a href="{{ route('lophoc.show', $lh->id) }}" style="text-decoration: none; color: inherit;">
    <div class="course-card">
        <div class="class-name">{{ $lh->tenlophoc }}</div>
        <div class="course-details">
            <div class="detail-row">
                <span class="label">Mã lớp học:</span>
                <span class="value">{!! $lh->malophoc !!}</span>
            </div>
            <div class="detail-row">
                <span class="label">Ngày bắt đầu :</span>
                <span class="value">{!! $lh->ngaybatdau !!}</span>
            </div>
            <div class="detail-row">
                <span class="label">Ngày kết thúc:</span>
                <span class="value">{{ $lh->ngayketthuc }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Trạng thái:</span>
                <span class="value">{{ $lh->trangthai }}</span>
            </div>

            <div class="attendance-section">
                <span class="status"></span>
                <span class="action-link">Xem chi tiết</span>
            </div>
        </div>
    </div>
</a>
@endforeach
@else
<div class="text-center w-100 py-5" style="grid-column: 1 / -1;">
    <h4 class="text-muted">Không tìm thấy lớp học nào.</h4>
</div>
@endif