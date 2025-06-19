<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrinhDo extends Model
{
    use HasFactory;

    protected $table = 'trinhdo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ma',
        'ten',
        'mota',
        'kynang_id'
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';


    public function kynang()
    {
        return $this->belongsTo(KyNang::class, 'kynang_id', 'id');
    }
    public function donGias()
    {
        return $this->hasMany(DonGia::class, 'trinhdo_id');
    }

    public function lopHocs()
    {
        return $this->hasMany(LopHoc::class, 'trinhdo_id');
    }
}
