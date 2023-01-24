<?php

namespace App\Models\System;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, Uuid;

    protected $table    = 'site_menus';
    protected $fillable = ['site_id', 'name', 'active'];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id');
    }
}
