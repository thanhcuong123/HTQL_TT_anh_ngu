@if(count($dstrinhdo) > 0)
@foreach($dstrinhdo as $td)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $td->ma }}</td>
    <td>{{ $td->ten }}</td>

    <td>{{ $td->kynang->ten??'chua có' }}</td>

    <td>
        @if ($td->dongia)
        {{ number_format($td->dongia->hocphi, 0, ',', '.') }} VNĐ
        @else
        Chưa có
        @endif
    </td>
    <td>{{ $td->dongia?->namhoc?->nam ?? 'Chưa có' }}</td>
    <td>{!! $td->mota !!}</td>

    <td class="col-action">
        <!-- <a href="#" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
        <a href="javascript:void(0);"
            class="btn btn-sm btn-warning btn-sua-trinhdo"
            data-id="{{ $td->ma }}"
            data-ten="{{ $td->ten }}"

            data-mota="{!!  htmlspecialchars($td->mota)!!}">
            Sửa
        </a>
        <form action="{{ route('trinhdo.destroy',$td->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
        </form>
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="8" class="text-center text-danger">
        Không tìm thấy kết quả nào phù hợp với từ khóa: <strong>{{ request('tu_khoa') }}</strong>
    </td>
</tr>
@endif