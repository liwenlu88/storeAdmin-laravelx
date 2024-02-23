<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIn(string $string, $menuIds)
 * @method static paginate(int $int)
 * @method static where(string $string, int $int)
 */
class Menu extends Model
{
    use HasFactory;

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

    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')->with('children');
    }
}