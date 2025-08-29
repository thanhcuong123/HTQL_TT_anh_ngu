<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaiLieuHocTap extends Model
{
    use HasFactory;

    protected $table = 'tailieuhoctap'; // Tên bảng trong database

    protected $primaryKey = 'id';

    protected $fillable = [
        'giaovien_id',
        'lophoc_id',
        'tentailieu',      // ĐÃ SỬA: Sử dụng tên không dấu cách
        'duongdanfile',   // ĐÃ SỬA: Sử dụng tên không dấu cách
        'loaifile',       // ĐÃ SỬA: Sử dụng tên không dấu cách
        'kichthuocfile',  // ĐÃ SỬA: Sử dụng tên không dấu cách
        'mota',           // ĐÃ SỬA: Sử dụng tên không dấu cách
    ];

    // Nếu bảng 'tai_lieu_hoc_tap' của bạn sử dụng 'create_at' và 'update_at' thay vì 'created_at' và 'updated_at'
    // Hãy thêm các dòng sau:
    // const CREATED_AT = 'create_at';
    // const UPDATED_AT = 'update_at';


    /**
     * Mối quan hệ: Tài liệu thuộc về một giáo viên.
     */
    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'giaovien_id');
    }

    /**
     * Mối quan hệ: Tài liệu có thể liên quan đến một lớp học.
     */
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'lophoc_id');
    }
}
