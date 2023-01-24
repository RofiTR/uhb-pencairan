<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalItem extends Model
{
    use HasFactory, Uuid;

    protected $fillable = ['proposal_id', 'name', 'qty', 'price', 'sub_total', 'credit'];
}
