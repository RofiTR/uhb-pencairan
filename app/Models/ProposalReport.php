<?php

namespace App\Models;

use App\Traits\Uuid;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalReport extends Model
{
    use HasFactory, Uuid, LogsActivity;

    protected $fillable = ['proposal_id', 'type_id', 'sppd', 'category_id', 'withdrawal', 'realization', 'saldo', 'sign', 'description', 'approver_id', 'status_id', 'user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('proposal_report')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function histories()
    {
        return $this->hasMany(ProposalHistory::class, 'proposal_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'context_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Configuration::class, 'status_id', 'id');
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

    public function sppds()
    {
        return $this->hasMany(ProposalReportSppd::class);
    }

    public function sppdDetail()
    {
        return $this->hasOne(ProposalReportSppd::class);
    }
}
