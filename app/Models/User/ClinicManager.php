<?php

namespace App\Models\User;

use App\Models\Clinic\Clinic;
use App\Models\Common\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicManager extends Model
{
    use HasFactory;

    protected $with = ["user"];
    protected $fillable = [
        'user_id',
        'clinic_id',
        'additional_info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
