<?php

namespace App\Http\Livewire\System\Configuration;

use App\Models\Configuration;
use App\Models\UserModel;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use WireUi\Traits\Actions;

class Form extends Component
{
    use Actions;

    public $configurations, $group, $name, $value, $users, $uuid;

    public function mount()
    {
        $this->configurations = Configuration::select('id', 'group', 'name', 'value')->orderBy('group', 'asc')->orderBy('sort_order', 'asc')->get()->toArray();
        $this->users          = UserModel::role('Pimpinan')->get()->map(function ($item, $key) {
            return ['label' => $item->full_name, 'value' => json_encode([$item->id, $item->full_name])];
        });
    }

    public function render()
    {
        return view('livewire.system.configuration.form');
    }

    public function save($key, $id)
    {
        try {
            Configuration::where('id', $id)->update($this->configurations[$key]);
            $this->notification([
                'title'       => NULL,
                'description' => 'Data berhasil disimpan',
                'icon'        => 'success'
            ]);
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
