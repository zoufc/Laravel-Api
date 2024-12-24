<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneMessage extends Model
{

    protected $fillable=[
        "user_id","otpCode","phoneNumber","expires_at"
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
