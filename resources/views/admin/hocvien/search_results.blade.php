@if(count($dshocvien) > 0)
@foreach($dshocvien as $kh)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $kh->mahocvien }}</td>
    <td>{{ $kh->ten }}</td>
    <td>{{ $kh->user->email}}</td>
    <td>{{ $kh->sdt }}</td>
    <td>{{ $kh->diachi }}</td>
    <td>{{ $kh->ngaysinh}}</td>
    <td>{{ $kh->gioitinh }}</td>
    <td>{{ $kh->ngaydangki }}</td>
    <td>{{ $kh->trangthai }}</td>
    <td class="col-action">
        <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
        <!-- <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-khoahoc"
                                data-id="{{ $kh->ma }}"
                                data-ten="{{ $kh->ten }}"
                                data-mota="{!!   htmlspecialchars($kh->mota) !!}"
                                data-thoiluong="{{ $kh->thoiluong }}"
                                data-sobuoi="{{ $kh->sobuoi }}">
                                Sửa
                            </a> -->

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