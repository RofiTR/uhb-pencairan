<?php

namespace App\Models;

use App\Traits\Uuid;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferHistory extends Model
{
    use HasFactory, Uuid, LogsActivity;

    protected $fillable = ['propsal_id', 'from', 'to'];

    protected $casts = [
        'from' => 'array',
        'to'   => 'array',
    ];

    protected $with = ['file'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('transfer_histories')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function file()
    {
        return $this->hasOne(File::class, 'context_id', 'id');
    }
}
