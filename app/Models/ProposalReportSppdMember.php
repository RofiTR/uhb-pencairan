<?php

namespace App\Models;

use App\Traits\Uuid;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalReportSppdMember extends Model
{
    use HasFactory, Uuid, LogsActivity;

    protected $fillable = ['proposal_report_sppd_id', 'user_id'];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('proposal_report_sppd_member')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}
