<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HocVien extends Model
{
    use HasFactory;

    protected $table = 'hocvien';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'mahocvien',
        'ten',
        'hinhanh',
        'sdt',
        'email_hv',
        'diachi',
        'ngaysinh',
        'gioitinh',
        'ngaydangki',
        'trangthai',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Trong model HocVien của bạn
    public function lopHocs()
    {
        return $this->belongsToMany(LopHoc::class, 'lophoc_hocvien', 'hocvien_id', 'lophoc_id')
            ->withPivot('ngaydangky', 'trangthai', 'created_at', 'updated_at'); // Đã sửa
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'hocvien_id');
    }

    // app/Models/HocVien.php
    public function phieuthu()
    {
        return $this->hasMany(PhieuThu::class, 'hocvien_id');
    }
    public function thongbaos()
    {
        return $this->hasMany(ThongBao::class, 'doituongnhan_id')->where('loaidoituongnhan', 'hoc_vien_cu_the');
    }
}
