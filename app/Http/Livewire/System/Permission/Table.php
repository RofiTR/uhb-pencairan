<?php

namespace App\Http\Livewire\System\Permission;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\System\Permission;

class Table extends DataTableComponent
{
    protected $model = Permission::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            // Column::make("Action", "id"),
            Column::make("Permission", "name")
                ->sortable()
                ->searchable(),
            Column::make("Guard", "guard_name")
        ];
    }
}
