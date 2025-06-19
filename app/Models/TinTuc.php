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

    public function tacGia()
    {
        return $this->belongsTo(User::class, 'tacgia_id');
    }
}
