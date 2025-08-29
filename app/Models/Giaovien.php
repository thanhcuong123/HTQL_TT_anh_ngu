<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoVien extends Model
{
    use HasFactory;

    protected $table = 'giaovien';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'magiaovien',
        'chucdanh_id',
        'hocvi_id',
        'chuyenmon_id',
        'ten',
        'hinhanh',
        'sdt',
        'ngaysinh',
        'gioitinh',
        'diachi',
        'stk',
        'trangthai',
        'email_gv'

    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chucDanh()
    {
        return $this->belongsTo(ChucDanh::class, 'chucdanh_id');
    }

    public function hocVi()
    {
        return $this->belongsTo(HocVi::class, 'hocvi_id');
    }

    public function chuyenMon()
    {
        return $this->belongsTo(ChuyenMon::class, 'chuyenmon_id');
    }

    public function lopHocs()
    {
        return $this->hasMany(LopHoc::class, 'giaovien_id');
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'giaovien_id');
    }

    public function thoiKhoaBieus()
    {
        return $this->hasMany(ThoiKhoaBieu::class, 'giaovien_id');
    }
}
