<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(mixed $key)
 * @method static paginate(int $pageSize)
 * @method static where(array[] $array)
 * @method static groupBy(string $string)
 */
class Recycle extends Base
{
    use HasFactory;

    protected $table = 'recycle';

    /**
     * 可批量分配.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'label',
        'type',
        'item_id'
    ];
}
