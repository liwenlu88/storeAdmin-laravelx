<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static whereIn(string $string, $menuIds)
 * @method static paginate(int $int)
 * @method static where(string $string, int $int)
 * @method static create(array $data)
 * @method static find(mixed $id)
 */
class Menu extends Base
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 可批量分配.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'icon',
        'parent_id',
        'order'
    ];
    
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')->with('children');
    }
}