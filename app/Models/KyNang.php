<?php

namespace App\Models;

use App\Http\Controllers\Admin\TrinhdoController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KyNang extends Model
{
    use HasFactory;

    protected $table = 'kynang';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ma',
        'ten',
        'mota',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    // public function trinhDos()
    // {
    //     return $this->belongsToMany(TrinhDo::class, 'trinhdo_kynang', 'kynang_id', 'trinhdo_id')
    //         ->withTimestamps();
    // }
    // public function trinhDos()
    // {
    //     return $this->hasMany(TrinhDo::class, 'kynang_id', 'id');
    // }
    public function thoiKhoaBieus()
    {
        return $this->hasMany(ThoiKhoaBieu::class, 'kynang_id');
    }
    public function trinhdo()
    {
        return $this->belongsToMany(
            TrinhDo::class,
            'trinhdo_kynang',   // Tên bảng pivot
            'kynang_id',        // FK của Kỹ năng trong pivot
            'trinhdo_id'        // FK của Trình độ trong pivot
        );
    }
}
