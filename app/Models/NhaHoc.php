<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaHoc extends Model
{
    use HasFactory;

    protected $table = 'nhahoc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ma',
        'coso_id',
        'ten',
        'diachi',
        'mota',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function coSo()
    {
        return $this->belongsTo(CoSo::class, 'coso_id');
    }

    public function tangs()
    {
        return $this->hasMany(Tang::class, 'nhahoc_id');
    }
}
