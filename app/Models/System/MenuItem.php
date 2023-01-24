<?php

namespace App\Models\System;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory, Uuid;

    protected $table    = 'site_menu_items';
    protected $fillable = ['menu_id', 'parent_id', 'name', 'permission', 'label', 'route', 'url', 'order'];

    public $translatable = ['label', 'route', 'url'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permission' => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(MenuItem::class, 'id', 'parent_id')->orderBy('order', 'ASC');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id')->orderBy('order', 'ASC');
    }

    public static function tree($name)
    {
        return static::with([
            implode('.', array_fill(0, 100, 'children'))
        ])->whereHas('menu', function (Builder $query) use ($name) {
            $query->where('name', $name);
        })->where('parent_id', '=', NULL)->orderBy('order', 'ASC')->get();
    }
}
