<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'thongbao';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tieude',
        'noidung',
        'nguoigui_id',
        'loaidoituongnhan',
        'doituongnhan_id',
        'ngaydang',
        'trangthai',
    ];

    public function nguoiGui()
    {
        return $this->belongsTo(User::class, 'nguoigui_id');
    }
    public function doiTuongNhanLopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'doituongnhan_id');
    }

    /**
     * Mối quan hệ cụ thể khi đối tượng nhận là HocVien.
     */
    public function doiTuongNhanHocVien()
    {
        return $this->belongsTo(HocVien::class, 'doituongnhan_id');
    }

    /**
     * Mối quan hệ động với đối tượng nhận (LopHoc hoặc HocVien).
     * Phương thức này sẽ trả về đối tượng đã được eager load nếu có.
     */
    // public function doiTuongNhan()
    // {
    //     if ($this->loaidoituongnhan === 'lop_hoc') {
    //         return $this->doiTuongNhanLopHoc; // Trả về thuộc tính đã được eager load
    //     } elseif ($this->loaidoituongnhan === 'hoc_vien_cu_the') {
    //         return $this->doiTuongNhanHocVien; // Trả về thuộc tính đã được eager load
    //     }
    //     return null;
    // }
}
