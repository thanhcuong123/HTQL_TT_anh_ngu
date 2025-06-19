<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhanvien';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'manhanvien',
        'chucdanh_id',
        'ten',
        'hinhanh',
        'sdt',
        'diachi',
        'ngaysinh',
        'gioitinh',
        'trangthai',
    ];

    const CREATED_AT = 'creat_at'; // Chú ý: có thể là 'create_at' hoặc 'creat_at' tùy thuộc vào schema của bạn
    const UPDATED_AT = 'update_at';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chucDanh()
    {
        return $this->belongsTo(ChucDanh::class, 'chucdanh_id');
    }

    public function phieuThus()
    {
        return $this->hasMany(PhieuThu::class, 'nhanvien_id');
    }
}
