<?php

namespace App\Http\Livewire\Proposal;

use Livewire\Component;
use App\Models\Proposal;
use App\Models\TransferHistory;

class Detail extends Component
{

    public $proposal, $transactions;

    public function mount($proposal)
    {
        $this->proposal     = $proposal;
        $this->transactions = TransferHistory::where('proposal_id', $proposal->id)->get();
    }

    public function render()
    {
        return view('livewire.proposal.detail');
    }
}
