@if(count($dsKhoaHoc) > 0)
@foreach($dsKhoaHoc as $kh)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $kh->ma }}</td>
    <td>{{ $kh->ten }}</td>

    <td>{{ $kh->namHoc->nam ?? 'N/A' }}</td>
    <td>
        @if ($kh->lopHocs->count() > 0)
        {{ $kh->lopHocs->first()->trinhDo->ten ?? 'N/A' }}
        @else
        N/A
        @endif
    </td>
    <td>{{ $kh->ngaybatdau ? \Carbon\Carbon::parse($kh->ngaybatdau)->format('d/m/Y') : '' }}</td>
    <td>{{ $kh->ngayketthuc ? \Carbon\Carbon::parse($kh->ngayketthuc)->format('d/m/Y') : '' }}</td>

    <td>{{ $kh->thoiluong }}</td>
    <td>{{ $kh->sobuoi }}</td>
    <td>
        @if ($kh->lopHocs->count() > 0 && $kh->lopHocs->first()->trinhDo->dongia)
        {{ number_format($kh->lopHocs->first()->trinhDo->dongia->hocphi, 0, ',', '.') }} VNĐ
        @else
        N/A
        @endif
    </td>
    <td>{{$kh->solop??'_'}}</td>
    <td>{!! Str::limit(strip_tags($kh->mota), 50) !!}</td>

    <td>
        @if($kh->hinhanh)
        <img src="{{ asset('storage/' . $kh->hinhanh) }}" alt="{{ $kh->ten }}" style="max-width: 100px;">
        @else
        Không có ảnh
        @endif
    </td>

    <td>
        <a href="{{ route('khoahoc.edit', $kh->id) }}" class="btn btn-sm btn-warning">Sửa</a>

        <form action="{{ route('khoahoc.destroy', $kh->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn?')">Xóa</button>
        </form>
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="14" class="text-center text-danger">
        Không tìm thấy kết quả nào phù hợp với từ khóa: <strong>{{ request('tu_khoa') }}</strong>
    </td>
</tr>
@endif