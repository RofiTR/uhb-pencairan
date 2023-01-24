<?php

namespace App\Http\Livewire\System\Role;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\System\Role;

class Table extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            // Column::make("Action", "id"),
            Column::make("Role", "name")
                ->sortable()
                ->searchable(),
            Column::make("Guard", "guard_name"),
        ];
    }
}
