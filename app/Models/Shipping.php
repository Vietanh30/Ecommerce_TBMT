<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shippings'; // Tên bảng trong database (có thể sửa nếu bạn dùng tên khác)

    protected $fillable = [
        'type',
        'price',
        'status',
    ];
}
