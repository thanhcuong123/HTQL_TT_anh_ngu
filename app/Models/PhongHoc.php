<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongHoc extends Model
{
    use HasFactory;

    protected $table = 'phonghoc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tang_id',
        'maphong',
        'tenphong',
        'succhua',
        'mota',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function tang()
    {
        return $this->belongsTo(Tang::class, 'tang_id');
    }

    public function thoiKhoaBieus()
    {
        return $this->hasMany(ThoiKhoaBieu::class, 'phonghoc_id');
    }
}
