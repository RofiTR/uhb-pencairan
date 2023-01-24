<?php

namespace App\Http\Livewire\Proposal;

use Livewire\Component;

class Tab extends Component
{
    public $tab = 'proposal';
    
    protected $listeners = [
        'setCategory' => 'setCategory'
    ];

    public function setCategory($category)
    {        
        $this->tab = $category;
    }
    
    public function render()
    {
        return view('livewire.proposal.tab');
    }
}
