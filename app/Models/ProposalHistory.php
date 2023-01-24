<?php

namespace App\Models;

use App\Traits\Uuid;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class ProposalHistory extends Model
{
    use HasFactory, Uuid, LogsActivity;

    protected $fillable = ['proposal_id', 'user_id', 'status_id', 'notes'];

    protected $with = ['status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('proposal_history')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function status()
    {
        return $this->hasOne(Configuration::class, 'id', 'status_id');
    }
}
