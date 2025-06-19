<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tang extends Model
{
    use HasFactory;

    protected $table = 'tang';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nhahoc_id',
        'ten',
        'mota',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function nhaHoc()
    {
        return $this->belongsTo(NhaHoc::class, 'nhahoc_id');
    }

    public function phongHocs()
    {
        return $this->hasMany(PhongHoc::class, 'tang_id');
    }
}
