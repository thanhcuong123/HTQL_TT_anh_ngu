@if(count($dshocvien) > 0)
@foreach($dshocvien as $kh)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $kh->mahocvien }}</td>
    <td>{{ $kh->ten }}</td>
    <td>{{ $kh->email_hv   }}</td>
    <td>{{ $kh->sdt }}</td>
    <td>{{ $kh->diachi }}</td>
    <td>{{ $kh->ngaysinh}}</td>
    <td>{{ $kh->gioitinh }}</td>
    <td>{{ $kh->ngaydangki }}</td>
    <td>
        @if ($kh->lophocs->count() > 0)
        @foreach ($kh->lophocs as $lh)
        <span>{{ $lh->tenlophoc }}</span>
        <br>
        @endforeach
        @else
        Chưa đăng ký
        @endif
    </td>
    <td>{{ $kh->trangthai }}</td>
    <td class="col-action">
        <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
        <a href="javascript:void(0);"
            class="btn btn-sm btn-warning btn-sua-hocvien"
            data-id="{{ $kh->id }}"
            data-ma="{{ $kh->mahocvien }}"
            data-ten="{{ $kh->ten }}"
            data-email="{{ $kh->user->email ?? '' }}"
            data-sdt="{{ $kh->sdt }}"
            data-diachi="{{ $kh->diachi }}"
            data-ngaysinh="{{ $kh->ngaysinh }}"
            data-gioitinh="{{ $kh->gioitinh }}"
            data-ngaydangki="{{ $kh->ngaydangki }}"
            data-trangthai="{{ $kh->trangthai }}">
            Sửa
        </a>

        <form action="" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
        </form>
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="12" class="text-center text-danger">
        Không tìm thấy kết quả nào phù hợp với từ khóa: <strong>{{ request('tu_khoa') }}</strong>
    </td>
</tr>
@endif