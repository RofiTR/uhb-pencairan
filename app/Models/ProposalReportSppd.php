<?php

namespace App\Models;

use App\Traits\Uuid;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalReportSppd extends Model
{
    use HasFactory, Uuid, LogsActivity;

    protected $fillable = ['proposal_report_id', 'destination', 'departure', 'arrive'];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('proposal_report_sppd')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function members()
    {
        return $this->hasMany(ProposalReportSppdMember::class);
    }

    public function items()
    {
        return $this->hasMany(ProposalReportSppdItem::class);
    }
}
