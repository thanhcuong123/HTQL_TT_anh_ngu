<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinTuc extends Model
{
    use HasFactory;

    protected $table = 'tintuc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tieude',
        'slug',
        'noidung',
        'ngaydang',
        'hinhanh',
        'tacgia_id',
        'trang_thai',
    ];

    public function tacgia()
    {
        return $this->belongsTo(User::class, 'tacgia_id');
    }
    public function nhanvien()
    {
        return $this->hasOneThrough(NhanVien::class, User::class, 'id', 'user_id', 'tacgia_id', 'id');
    }
}
