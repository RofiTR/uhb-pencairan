<?php

namespace App\Http\Livewire\System;

use Livewire\Component;
use WireUi\Traits\Actions;
use Usernotnull\Toast\ToastManager;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Notification extends Component
{
    use Actions;

    public array $toasts = [];

    protected $listeners = [
        'getNotification' => '$refresh'
    ];

    public function dehydrate(): void
    {
        ToastManager::setComponentRendered(true);

        $this->toasts = array_merge($this->toasts, ToastManager::pull());
    }

    public function mount()
    {
        if (session()->has(config('tall-toasts.session_keys.toasts_next_page'))) {
            $this->toasts = ToastManager::pullNextPage();
        }
    }

    public function render()
    {
        foreach ($this->toasts as $toast) {
            $this->notification([
                'title'       => $toast['title'],
                'description' => $toast['message'],
                'icon'        => $toast['type'],
            ]);
        }
        return view('livewire.system.notification');
    }
}
