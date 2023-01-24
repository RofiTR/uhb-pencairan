<?php

namespace App\Models;

use App\Traits\Uuid;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory, Uuid, LogsActivity;

    protected $fillable = ['no', 'type_id', 'category_id', 'voucher_no', 'name', 'description', 'withdrawal', 'approver_id', 'status_id', 'user_id'];

    protected $casts = [
        'withdrawal' => 'array',
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('proposal')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function histories()
    {
        return $this->hasMany(ProposalHistory::class, 'proposal_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'context_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Configuration::class, 'category_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Configuration::class, 'type_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(UserModel::class, 'approver_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Configuration::class, 'status_id', 'id');
    }

    public function report()
    {
        return $this->hasOne(ProposalReport::class);
    }
}
