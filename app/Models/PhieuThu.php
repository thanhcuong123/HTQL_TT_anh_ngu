<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuThu extends Model
{
    use HasFactory;

    protected $table = 'phieuthu';

    protected $primaryKey = 'id';

    protected $fillable = [
        'hocvien_id',
        'lophoc_id',
        'sotien',
        'ngaythanhtoan',
        'phuongthuc',
        'ghichu',
        'nhanvien_id',
        'trangthai',
    ];

    public function hocVien()
    {
        return $this->belongsTo(HocVien::class, 'hocvien_id');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'lophoc_id');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhanvien_id');
    }
}
