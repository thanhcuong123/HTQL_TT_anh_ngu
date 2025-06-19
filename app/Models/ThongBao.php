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
}
