<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemDanh extends Model
{
    use HasFactory;

    protected $table = 'diemdanh';

    protected $primaryKey = 'id';

    protected $fillable = [
        'lophoc_id',
        'hocvien_id',
        'giaovien_id',
        'thoikhoabieu_id',
        'ngaydiemdanh',
        'thoigiandiemdanh',
        'trangthaidiemdanh',
        'ghichu',
    ];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'lophoc_id');
    }

    public function hocVien()
    {
        return $this->belongsTo(HocVien::class, 'hocvien_id');
    }

    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'giaovien_id');
    }

    public function thoiKhoaBieu()
    {
        return $this->belongsTo(ThoiKhoaBieu::class, 'thoikhoabieu_id');
    }
}
