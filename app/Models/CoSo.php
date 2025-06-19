<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoSo extends Model
{
    use HasFactory;
    protected $table = 'coso';
    protected $primarykey = 'id';
    protected $fillable = [
        'macoso',
        'tencoso',
        'diachi',
        'sdt',
        'email',
        'mota'
    ];
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';
    public function nhaHocs()
    {
        return $this->hasMany(NhaHoc::class, 'coso_id');
    }

    //
}
