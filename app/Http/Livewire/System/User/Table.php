<?php

namespace App\Http\Livewire\System\User;

use App\Models\System\Role;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\UserModel;
use WireUi\Traits\Actions;

class Table extends DataTableComponent
{
    use Actions;

    protected $model = UserModel::class;

    public string $tableName = 'user-table';
    public $permanently, $deleteModal, $addModal, $user = [], $roles, $role = [];

    public function mount()
    {
        // $this->_getUsers();
        $this->roles = Role::get()->toArray();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('name', 'asc');
        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'livewire.system.user.table.left-button', [
                    'roles' => $this->roles,
                ]
            ],
            'after-pagination'   => 'partials.confirmation-delete-modal',
        ]);
    }

    public function columns(): array
    {
        $columns = [
            Column::make("Name", "full_name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email"),
        ];

        if (user()->can('user.edit') || user()->can('user.delete'))
            array_unshift($columns, Column::make("Aksi", "id")->view('livewire.system.user.table.action-button'));

        return $columns;
    }

    public function save()
    {
        foreach ($this->user as $key => $value) {
            $u = User::find($value);
            $user = UserModel::updateOrCreate(
                [
                    'id'        => $u->id,
                ],
                [
                    'name'      => $u->name,
                    'full_name' => $u->full_name,
                    'email'     => $u->email
                ]
            );
            $user->syncRoles($this->role);
        }
        // $this->_getUsers();
        $this->user = [];
        $this->role = [];
    }

    public function delete()
    {
        $user = UserModel::find($this->user);
        if ($this->permanently)
            $user->forceDelete();
        $user->delete();
        $this->notification([
            'title'       => NULL,
            'description' => 'Data berhasil dihapus',
            'icon'        => 'success'
        ]);
        // $this->_getUsers();
        $this->user        = '';
        $this->permanently = '';
        $this->emit('refreshDatatable');
    }

    // private function _getUsers()
    // {
    //     $currents = UserModel::get()->pluck('id');
    //     $this->users = User::whereNotIn('id', $currents)->whereIn('position', [2, 4, 5])->orderBy('name', 'asc')->get()->toArray();
    // }
}
