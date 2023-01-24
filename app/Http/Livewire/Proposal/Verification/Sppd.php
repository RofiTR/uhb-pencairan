<?php

namespace App\Http\Livewire\Proposal\Verification;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\Configuration;
use App\Models\ProposalHistory;
use App\Models\ProposalReportSppd;
use App\Models\ProposalReportSppdItem;

class Sppd extends Component
{
    use Actions;

    public $sppdId, $ids = [], $items = [], $prices = [], $quantities = [], $credits = [], $members = [], $total = 0;
    public $inputs = [];
    public $i = 1;

    protected $listeners = [
        'setSppd' => 'setSppd'
    ];

    public function setSppd(ProposalReportSppd $sppd)
    {
        $this->sppdId     = $sppd;
        foreach ($sppd->items as $key => $value) {
            $this->inputs[$key] = $key;
            $this->ids[$key] = $value->id;
            $this->items[$key] = $value->name;
            $this->prices[$key] = $value->price;
            $this->quantities[$key] = $value->qty;
            $this->credits[$key] = $value->credit;
            $this->total += $value->credit;
        }
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
    }

    public function remove($i)
    {
        if (isset($this->credits[$i]))
            unset($this->credits[$i]);
        unset($this->inputs[$i]);
        $this->total = array_sum($this->credits);
    }

    public function render()
    {
        return view('livewire.proposal.verification.sppd');
    }

    public function updated($value)
    {
        $key = explode('.', $value);
        if ($key[0] == 'quantities') {
            $this->credits[$key[1]] = $this->prices[$key[1]] * $this->quantities[$key[1]];
            $this->total = array_sum($this->credits);
        }
    }

    public function save()
    {
        foreach ($this->inputs as $key => $value) {
            $data = [
                'proposal_report_sppd_id' => $this->sppdId->id,
                'name'                    => $this->items[$key],
                'qty'                     => $this->quantities[$key],
                'price'                   => $this->prices[$key],
                'sub_total'               => $this->quantities[$key] * $this->prices[$key],
                'credit'                  => $this->credits[$key],
            ];

            if (isset($this->ids[$key])) {
                ProposalReportSppdItem::where(['id' => $this->ids[$key]])->update($data);
            } else {
                ProposalReportSppdItem::create($data);
            }
        }
        $this->emitTo('proposal.verification.report', 'setTotal', $this->total);
        $this->i          = 1;
        $this->inputs     = [];
        $this->ids        = [];
        $this->items      = [];
        $this->prices     = [];
        $this->quantities = [];
        $this->credits    = [];
        $this->members    = [];
        $this->total      = 0;
    }
}
