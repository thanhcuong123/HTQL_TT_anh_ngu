<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thu extends Model
{
    use HasFactory;

    protected $table = 'thu';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tenthu',
        'thutu'
    ];

    // THÊM HAI DÒNG NÀY ĐỂ KHỚP VỚI TÊN CỘT TRONG CSDL CỦA BẠN
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';
    public function thoiKhoaBieus()
    {
        return $this->belongsToMany(ThoiKhoaBieu::class, 'thoi_khoa_bieu_thu', 'thu_id', 'thoi_khoa_bieu_id');
        // Lưu ý: Đây chỉ là ví dụ nếu bạn có bảng trung gian. 
        // Nếu bạn lưu 'thu_ids' dưới dạng JSON trong ThoiKhoaBieu, thì không cần mối quan hệ này.
    }
}
