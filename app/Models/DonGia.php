<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonGia extends Model
{
    use HasFactory;

    protected $table = 'dongia';

    protected $primaryKey = 'id';

    protected $fillable = [
        'trinhdo_id',
        'namhoc_id',
        'hocphi',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function trinhdo()
    {
        return $this->belongsTo(TrinhDo::class, 'trinhdo_id');
    }

    public function namHoc()
    {
        return $this->belongsTo(NamHoc::class, 'namhoc_id');
    }
}
