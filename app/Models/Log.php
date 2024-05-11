<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'method',
        'ip_address',
        'user_agent',
        'request_header',
        'request_body',
        'response_status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
