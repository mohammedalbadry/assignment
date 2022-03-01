<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "user_id",
        "pharmacy_id",
        "start_time",
        "end_time"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

}
