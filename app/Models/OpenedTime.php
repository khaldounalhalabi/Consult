<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenedTime extends Model
{
    use HasFactory;
    protected $table = 'opened_times';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'expert_id',
        'saturday_from',
        'saturday_to',
        'sunday_from',
        'sunday_to',
        'monday_from',
        'monday_to',
        'tuesday_from',
        'tuesday_to',
        'wednesday_from',
        'wednesday_to',
        'thursday_from',
        'thursday_to',
        'friday_from',
        'friday_to',
    ];
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
