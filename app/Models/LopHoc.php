<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    use HasFactory;

    protected $table = 'lophoc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'khoahoc_id',
        'trinhdo_id',
        'giaovien_id',
        'tenlophoc',
        'hinhanh',
        'malophoc',
        'ngaybatdau',
        'ngayketthuc',
        'soluonghocvientoida',
        'soluonghocvienhientai',
        'trangthai',
        'mota',
        'lichoc'
    ];

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'khoahoc_id');
    }

    // Đảm bảo có cột 'trinhdo_id' trong bảng lophoc
    public function trinhdo()
    {
        return $this->belongsTo(TrinhDo::class, 'trinhdo_id');
    }

    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'giaovien_id');
    }

    // app/Models/LopHoc.php
    public function hocviens()
    {
        return $this->belongsToMany(HocVien::class, 'lophoc_hocvien', 'lophoc_id', 'hocvien_id')
            ->withPivot('ngaydangky') // << THÊM DÒNG NÀY NẾU CHƯA CÓ
            ->withTimestamps(); // Nếu bạn có timestamps trên bảng pivot
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'lophoc_id');
    }

    public function phieuThus()
    {
        return $this->hasMany(PhieuThu::class, 'lophoc_id');
    }

    public function thoiKhoaBieus()
    {
        return $this->hasMany(ThoiKhoaBieu::class, 'lophoc_id');
    }
}
