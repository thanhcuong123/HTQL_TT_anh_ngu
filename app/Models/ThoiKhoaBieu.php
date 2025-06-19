<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThoiKhoaBieu extends Model
{
    use HasFactory;

    protected $table = 'thoikhoabieu';

    protected $primaryKey = 'id';

    protected $fillable = [
        'lophoc_id',
        'giaovien_id',
        'phonghoc_id',
        'thu_id',
        'cahoc_id',
        'kynang_id',
        'ngayhoc',
    ];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'lophoc_id');
    }

    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'giaovien_id');
    }

    public function phongHoc()
    {
        return $this->belongsTo(PhongHoc::class, 'phonghoc_id');
    }

    public function thu()
    {
        return $this->belongsTo(Thu::class, 'thu_id');
    }

    public function caHoc()
    {
        return $this->belongsTo(CaHoc::class, 'cahoc_id');
    }

    public function kyNang()
    {
        return $this->belongsTo(KyNang::class, 'kynang_id');
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'thoikhoabieu_id');
    }
}
