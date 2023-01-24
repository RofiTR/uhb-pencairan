<?php

namespace App\Http\Livewire\Proposal\Report;

use App\Models\ProposalReport;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class Table extends DataTableComponent
{
    // protected $model = ProposalReport::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        $query  = ProposalReport::query()->select('configurations.value AS status', 'proposal_id', 'type.value AS sppd')
            ->join('configurations AS type', 'type.id', 'proposal_reports.type_id')
            ->join('configurations', 'configurations.id', 'proposal_reports.status_id');

        if (user()->hasRole('Keuangan')) {
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->where('value', '1');
                }
            );
        } elseif (user()->hasRole('Pimpinan')) {
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->where('value', '2');
                }
            );
        }

        return $query;
    }

    public function columns(): array
    {
        $columns = [
            Column::make("Aksi", "id")
                ->view('livewire.proposal.report.table.action-button')
        ];

        if ($this->page != 'dashboard')
            array_push($columns, Column::make("Pemohon", "staff.name")->sortable());

        $columns = array_merge($columns, [
            Column::make("Kegiatan", "proposal.name")
                ->sortable(),
        ]);

        $columns = array_merge($columns, [
            Column::make("Status", "status.name")
                ->sortable(),
            Column::make("Tgl Pengajuan", "created_at")
                ->sortable()
        ]);

        return $columns;
    }
}
