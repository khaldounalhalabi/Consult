<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReview extends Model
{
    use HasFactory;
    protected $table = 'comment_reviews';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'expert_id',
        'user_id',
        'comment',
        'star_rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
