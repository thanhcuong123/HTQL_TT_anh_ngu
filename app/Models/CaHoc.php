<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaHoc extends Model
{
    use HasFactory;
    protected $table = 'cahoc';
    protected $primaryKey = 'id';
    protected $fillable = [
        'maca',
        'tenca',
        'thoigianbatdau',
        'thoigianketthuc',
        'ghichu'
    ];

    public function thoiKhoaBieus()
    {
        return $this->hasMany(ThoiKhoaBieu::class, 'cahoc_id');
    }
    //
}
