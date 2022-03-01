<?php

namespace App\Models;

use App\Models\Timetable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function timetabls()
    {
        return $this->hasMany(Timetable::class);
    }
}
