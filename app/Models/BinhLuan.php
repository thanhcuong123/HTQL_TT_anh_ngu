<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;
    protected $table = 'binhluan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'noidung',
        'doituonglienquan_type',
        'doituonglienquan_id',
        'binhluancha_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function binhLuanCha()
    {
        return $this->belongsTo(BinhLuan::class, 'binhluancha_id');
    }
    public function binhLuanCon()
    {
        return $this->belongsTo(BinhLuan::class, 'binhluancha_id');
    }
}
