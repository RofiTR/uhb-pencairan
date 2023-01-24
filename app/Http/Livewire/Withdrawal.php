<?php

namespace App\Http\Livewire;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\Configuration;
use App\Models\ProposalHistory;

class Withdrawal extends Component
{
    use Actions;

    public $proposal, $voucher, $notes;

    public function mount($proposal)
    {
        $this->proposal = $proposal;
        $this->voucher  = $proposal->voucher_no;
        $history = $proposal->histories->where('user_id', user_id())->first();
        $this->notes    = $history ? $history->notes : NULL;
    }

    public function render()
    {
        return view('livewire.withdrawal');
    }

    public function save()
    {
        try {
            $status = Configuration::where('group', 'proposal status')->where('value', 6)->first();
    
            $this->proposal->status_id    = $status->id;
            $this->proposal->voucher_no   = $this->voucher;
            $this->proposal->save();
            
            ProposalHistory::create([
                'proposal_id' => $this->proposal->id,
                'user_id'     => user_id(),
                'status_id'   => $status->id,
                'notes'       => $this->notes
            ]);
            
            toast()->success('Data berhasil disimpan')->pushOnNextPage();
            return redirect()->route('withdrawal.index');
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
