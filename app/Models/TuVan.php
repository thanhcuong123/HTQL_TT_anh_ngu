<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TuVan extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'tuvan';

    // Khóa chính của bảng
    protected $primaryKey = 'id';

    // Các trường có thể được gán giá trị hàng loạt (mass assignable)
    protected $fillable = [
        'hoten',
        'email',
        'sdt',
        'dotuoi',
        'khoahoc_id',
        'loinhan',
        'trangthai', // Thêm trường trạng thái
        'ghichu',    // Thêm trường ghi chú
    ];

    // Nếu bạn không sử dụng các cột `created_at` và `updated_at` mặc định của Laravel
    // mà thay vào đó là `create_at` và `update_at` như trong model KhoaHoc của bạn,
    // thì bạn cần định nghĩa chúng ở đây.
    // Nếu bạn dùng tên mặc định `created_at` và `updated_at` trong SQL, bạn có thể bỏ 2 dòng dưới.
    const CREATED_AT = 'created_at'; // Đảm bảo khớp với tên cột trong DB
    const UPDATED_AT = 'updated_at'; // Đảm bảo khớp với tên cột trong DB

    /**
     * Định nghĩa mối quan hệ: Một yêu cầu tư vấn thuộc về một KhoaHoc.
     */
    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'khoahoc_id');
    }
}
