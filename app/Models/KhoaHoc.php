<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'khoahoc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ma',
        'ten',
        'mota',
        'thoiluong',
        'sobuoi',
        'hinhanh',

    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function lopHocs()
    {
        return $this->hasMany(LopHoc::class, 'khoahoc_id');
    }
    public function TuVans()
    {
        return $this->hasMany(TuVan::class, 'khoahoc_id');
    }
}
