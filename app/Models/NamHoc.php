<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamHoc extends Model
{
    use HasFactory;

    protected $table = 'namhoc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nam',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function dongia()
    {
        return $this->hasMany(DonGia::class, 'namhoc_id');
    }
}
