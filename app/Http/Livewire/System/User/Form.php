<?php

namespace App\Http\Livewire\System\User;

use App\Models\System\Permission;
use App\Models\UserModel;
use Livewire\Component;
use App\Models\System\Role;
use WireUi\Traits\Actions;

class Form extends Component
{
    use Actions;

    public $roles, $permissions, $user, $role, $permission;

    protected $rules = [
        'role' => 'required'
    ];

    public function mount($user = NULL)
    {
        $this->roles       = Role::get()->toArray();
        $this->permissions = Permission::get()->toArray();
        if ($user) {
            $this->user       = UserModel::find($user);
            $this->role       = $this->user->roles->pluck('name', 'name');
            $this->permission = $this->user->permissions->pluck('name', 'name');
            // dd($this->role, $this->permission);
        }
    }

    public function render()
    {
        return view('livewire.system.user.form');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();

        try {
            // dd($this->role, $this->permission);
            $this->user->syncRoles($this->role);
            $this->user->syncPermissions($this->permission);
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
