<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Uuid;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuid, HasRoles;

    protected $connection = 'dapo';
    protected $appends    = ['full_name'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id'];

    protected $casts = [
        'title' => 'array'
    ];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->title) {
                    $name = $this->title['prefix'] ? $this->title['prefix'] . ' ' . $this->name : $this->name;
                    $name .= $this->title['sufix'] ? ', ' . $this->title['sufix'] : '';
                    return $name;
                }
                return $this->name;
            },
        );
    }
}
