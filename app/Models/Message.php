<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'body' ,
        'from' ,
        'user_id',
        'expert_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class) ;
    }
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
