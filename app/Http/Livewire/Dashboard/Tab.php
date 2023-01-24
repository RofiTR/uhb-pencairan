<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class Tab extends Component
{
    public $tab = 'pk';
    
    protected $listeners = [
        'setCategory' => 'setCategory'
    ];

    public function setCategory($category)
    {        
        $this->tab = $category;
    }
    
    public function render()
    {
        return view('livewire.dashboard.tab');
    }
}
