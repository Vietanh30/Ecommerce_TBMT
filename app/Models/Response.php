<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\User;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
        'content',
        'status',
    ];

    // Liên kết với đánh giá
    public function review()
    {
        return $this->belongsTo(ProductReview::class, 'review_id');
    }

    // Liên kết với người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
