<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thu extends Model
{
    use HasFactory;

    protected $table = 'thu';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tenthu',
        'thutu'
    ];

    // THÊM HAI DÒNG NÀY ĐỂ KHỚP VỚI TÊN CỘT TRONG CSDL CỦA BẠN
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';
}
