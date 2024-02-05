<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}