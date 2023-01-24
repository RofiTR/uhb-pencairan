<?php

namespace App\Models\System;

use App\Models\UserModel;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = 'system_notifications';
    protected $fillable = ['context', 'context_id', 'causer_id', 'description', 'user_id', 'is_read'];

    public function causer()
    {
        return $this->belongsTo(UserModel::class);
    }
}
