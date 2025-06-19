<?php

namespace App\Models;

use iLLuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucDanh extends Model
{
    use HasFactory;
    protected $table = 'chucdanh';
    protected $primarykey = 'id';
    protected $fillable = [
        'ten',
        'mota'
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function nhaViens()
    {
        return $this->hasMany(NhanVien::class, 'chucdanh_id');
    }
    public function giaoViens()
    {
        return $this->hasMany(GiaoVien::class, 'chucdanh_id');
    }
    //
}
