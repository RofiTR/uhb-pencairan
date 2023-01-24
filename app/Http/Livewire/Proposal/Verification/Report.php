<?php

namespace App\Http\Livewire\Proposal\Verification;

use Livewire\Component;
use App\Models\UserModel;
use WireUi\Traits\Actions;
use App\Models\Configuration;
use App\Models\ProposalHistory;
use Illuminate\Support\Facades\DB;

class Report extends Component
{
    use Actions;

    public $proposal, $report, $approvers, $statuses, $disable, $isSppd;
    public $amount, $realization = 0, $saldo, $sign, $approver, $notes, $verificationStatus;

    protected $rules = [
        'realization'        => 'required|gt:0',
        'approver'           => 'required',
        'verificationStatus' => 'required',
    ];

    protected $validationAttributes = [
        'realization'        => 'realisasi',
        'approver'           => 'pimpinan',
        'verificationStatus' => 'Verifikasi LPJ'
    ];

    protected $listeners = [
        'setTotal' => 'setTotal'
    ];

    public function setTotal($value)
    {
        $this->realization += $value;
    }

    public function mount($proposal)
    {
        $this->disable     = FALSE;
        $this->isSppd        = FALSE;
        $this->proposal    = $proposal;
        $this->report      = $proposal->report;
        $this->amount      = $this->proposal->amount;
        $this->realization = $this->report->realization;
        $this->saldo       = $this->report->saldo;
        $this->approver    = $this->report->approver_id;
        $this->approvers   = Configuration::where('group', 'approver')->get();
        $this->approvers   = $this->approvers->map(function ($item, $key) {
            $value = json_decode($item['value']);
            return ['label' => $value[1], 'value' => $value[0], 'description' => 'Limit Rp. ' . number_format($item['name'], 2, ',', '.'), 'limit' => $item['name']];
        });

        $history = $this->report->histories->where('user_id', user_id())->first();
        if ($history) {
            if ((user()->hasRole('Keuangan') && $history->status->value >= 2) || (user()->hasRole('Pimpinan') && $history->status->value >= 5)) {
                $this->verificationStatus = $history ? (int) $history->status->value : NULL;
                $this->notes              = $history ? $history->notes : NULL;
                $this->disable            = TRUE;
            }
        }

        $last_history = $this->report->histories->last();
        if (user()->hasRole('Keuangan'))
            $this->statuses = [['label' => 'Terima', 'value' => 2], ['label' => 'Tolak', 'value' => 3]];
        if (user()->hasRole('Pimpinan') && $last_history->status->value == 2 && $proposal->type->value == 1) {
            $this->statuses = [['label' => 'Terima', 'value' => 4], ['label' => 'Tolak', 'value' => 5]];
            $this->isSppd = TRUE;
        } else if (user()->hasRole('Pimpinan'))
            $this->statuses = [['label' => 'Terima', 'value' => 6], ['label' => 'Tolak', 'value' => 7]];
    }

    public function render()
    {
        return view('livewire.proposal.verification.report');
    }

    public function updatedRealization($value)
    {
        $saldo = (int) str_replace('.', '', $this->amount) - str_replace('.', '', $value);
        $this->saldo = number_format($saldo, 0, '.', ',');

        $this->approver = $this->approvers->firstWhere('limit', '>=', str_replace('.', '', $value)) ?? $this->approvers->last();
        $this->approver = $this->approver['value'];
    }

    public function verificate()
    {
        $this->realization = str_replace('.', '', $this->realization);
        $this->validate();
        $status = Configuration::where('group', 'report status')->where('value', $this->verificationStatus)->first();

        $this->report->status_id   = $status->id;
        $this->report->withdrawal  = str_replace('.', '', $this->amount);
        $this->report->realization = $this->realization;
        $this->report->saldo       = str_replace('.', '', $this->saldo);
        $this->report->sign        = $this->realization > $this->amount ? '-' : '+';
        $this->report->approver_id = $this->approver;

        try {
            DB::transaction(function () use ($status) {
                $this->report->save();

                ProposalHistory::create([
                    'proposal_id' => $this->report->id,
                    'user_id'     => user_id(),
                    'status_id'   => $status->id,
                    'notes'       => $this->notes
                ]);

                if ($this->verificationStatus == 6) {
                    $status = Configuration::where('group', 'report status')->where('value', 8)->first();
                    $this->report->status_id = $status->id;
                    ProposalHistory::create([
                        'proposal_id' => $this->report->id,
                        'user_id'     => user_id(),
                        'status_id'   => $status->id,
                        'notes'       => $this->notes
                    ]);

                    $status = Configuration::where('group', 'proposal status')->where('value', 7)->first();
                    $this->proposal->status_id = $status->id;
                    $this->proposal->save();

                    ProposalHistory::create([
                        'proposal_id' => $this->proposal->id,
                        'user_id'     => user_id(),
                        'status_id'   => $status->id,
                        'notes'       => $this->notes
                    ]);

                    $user = UserModel::find($this->report->user_id);
                    $user_limit = $user->limit;
                    $user_limit[$this->report->category->name] -= 1;

                    $user->limit     = $user_limit;
                    $user->save();
                }
            });
            toast()->success('Data berhasil disimpan')->pushOnNextPage();

            if ($this->isSppd && $this->report->approver_id == user_id())
                return redirect()->route('verification.show', ['type' => 'lpj', 'proposal' => $this->proposal->id]);
            else
                return redirect()->route('lpj.index');
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
