<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuyenMon extends Model
{
    use HasFactory;
    protected $table = 'chuyenmon';
    protected $primarykey = 'id';
    protected $fillable = [
        'tenchuyenmon',
        'mota'
    ];
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';
    public function giaoViens()
    {
        return $this->hasMany(GiaoVien::class, 'chuyenmon_id');
    }
}
