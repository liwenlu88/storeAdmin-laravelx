<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, int $int)
 */
class Authority extends Base
{
    use HasFactory;

    protected $table = 'authority';

    // 转换 menu_id 为数组
    protected $casts = [
        'menu_id' => 'array',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'user_id');
    }
}