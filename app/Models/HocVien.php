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

    public function lopHocs()
    {
        return $this->belongsToMany(LopHoc::class, 'lophoc_hocvien', 'hocvien_id', 'lophoc_id')
            ->withPivot('ngaydangky', 'trangthai')
            ->withTimestamps(); // Nếu bảng pivot có created_at/updated_at
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'hocvien_id');
    }

    public function phieuThus()
    {
        return $this->hasMany(PhieuThu::class, 'hocvien_id');
    }
}
