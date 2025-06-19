<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HocVi extends Model
{
    use HasFactory;

    protected $table = 'hocvi';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tenhocvi',
        'mota',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function giaoViens()
    {
        return $this->hasMany(GiaoVien::class, 'hocvi_id');
    }
}
