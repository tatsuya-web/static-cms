<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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

    public static function getAllThisMonth(): Collection
    {
        return self::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();
    }
}
