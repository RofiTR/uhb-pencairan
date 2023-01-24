<?php

namespace App\Http\Livewire\Proposal;

use App\Models\Proposal;
use App\Models\Configuration;
use App\Models\ProposalHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use WireUi\Traits\Actions;

class Table extends DataTableComponent
{
    use Actions;
    // protected $model = Proposal::class;

    public $page, $category, $proposal, $verificationModal, $verificationStatus, $notes, $amount, $approver;

    protected $listeners = [
        'setCategory' => 'setCategory'
    ];

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function mount($page, $category = NULL)
    {
        $this->page     = $page;
        $this->category = $category;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        if ($this->page == 'dashboard') {
            $href = [];
            if (user()->limit[$this->category] < 2) {
                $href = [
                    'href' => route('proposal.create', ['category' => $this->category]),
                ];
            }
            $this->setConfigurableAreas([
                'toolbar-left-start' => [
                    'partials.table.toolbar-left-start', $href
                ],
            ]);
        }
    }

    public function builder(): Builder
    {
        $query  = Proposal::query()->select('prop.value AS status', 'prop.name AS status_prop', 'lpj.name AS status_report', 'proposals.withdrawal')
            ->leftJoin('proposal_reports', 'proposal_reports.proposal_id', 'proposals.id')
            ->leftJoin('configurations AS prop', 'prop.id', 'proposals.status_id')
            ->leftJoin('configurations AS lpj', 'lpj.id', 'proposal_reports.status_id')
            ->whereHas(
                'status',
                function ($query) {
                    return $query->where('value', '<>', '8');
                }
            );

        if ($this->page == 'verification' && user()->hasRole('Keuangan')) {
            // $query->where('status_id', '9e88270f-41ba-4051-8006-233776fb938c');
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->where('value', '1');
                }
            );
        } elseif ($this->page == 'verification' && user()->hasRole('Pimpinan')) {
            // $query->where('status_id', '8205b0fd-19a8-413a-96d2-fb880b110a82')
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->where('value', '2');
                }
            )->where('proposals.approver_id', user_id());
        } elseif ($this->page == 'withdrawal' && user()->hasRole('Kasir')) {
            // $query->where('status_id', '911de3a4-9f51-47b9-af67-f0242c459dc9');
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->whereIn('value', [4, 6]);
                }
            );
        } elseif ($this->page == 'report') {
            // $query->where('status_id', '911de3a4-9f51-47b9-af67-f0242c459dc9');
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->whereIn('value', [2, 3, 4, 5, 6]);
                }
            );
        } else {
            $query->whereHas(
                'category',
                function ($query) {
                    return $query->where('name', 'ilike', '%' . $this->category . '%');
                }
            );
        }
        if ($this->page == 'dashboard') {
            $query->whereHas(
                'status',
                function ($query) {
                    return $query->where('value', '<>', 7);
                }
            );
            $query->where('proposals.user_id', user_id());
        }
        if ($this->page == 'history') {
            $query->where('proposals.user_id', user_id());
        }

        return $query;
    }

    public function columns(): array
    {
        $columns = [
            Column::make("Aksi", "id")
                ->view('livewire.proposal.table.action-button')
        ];

        if ($this->page != 'dashboard')
            array_push($columns, Column::make("Pemohon", "staff.name")->sortable());

        $columns = array_merge($columns, [
            Column::make("Kegiatan", "name")
                ->sortable(),
            Column::make("Nominal", "amount")
                ->sortable()
                ->format(fn ($value) => number_format($value, 0, ',', '.')),
        ]);

        if ($this->page == 'dashboard')
            array_push($columns, Column::make("Pimpinan", "approver.name")->sortable());

        $columns = array_merge($columns, [
            Column::make("Status", "id")
                ->sortable()
                ->format(fn ($value, $row) => $row->status_report ? 'LPJ ' . $row->status_report : $row->status_prop),
            Column::make("Tgl Pengajuan", "created_at")
                ->sortable()
        ]);

        return $columns;
    }

    public function verification()
    {
        try {
            $status = Configuration::where('group', 'proposal status')->where('value', $this->verificationStatus)->first();
            $this->proposal->status_id = $status->id;
            $this->proposal->amount = $this->amount;
            $this->proposal->save();
            ProposalHistory::create([
                'proposal_id' => $this->proposal->id,
                'user_id'     => user_id(),
                'status_id'   => $status->id,
                'notes'       => $this->notes
            ]);
            $this->notification([
                'title'       => NULL,
                'description' => 'Pengajuan ' . strtolower($status->name),
                'icon'        => 'success'
            ]);
            $this->proposal           = NULL;
            $this->notes              = NULL;
            $this->verificationStatus = NULL;
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
