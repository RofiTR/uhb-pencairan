<?php

namespace App\Http\Livewire\Proposal\Verification;

use Livewire\Component;
use App\Models\UserModel;
use WireUi\Traits\Actions;
use App\Models\Configuration;
use App\Models\ProposalHistory;
use App\Models\System\Notification;
use App\Models\Proposal as ProposalModel;

class Proposal extends Component
{
    use Actions;

    public $page, $proposal, $disable, $verificationStatus, $notes, $amount, $approver, $statuses, $account, $voucher;

    public function mount($page, $proposal, $disable = FALSE)
    {
        $this->page     = $page;
        $this->proposal = $proposal;
        $this->disable  = $disable;

        $history = $this->proposal->histories->where('user_id', user_id())->first();
        if ($this->page == 'verification' && (user()->hasRole('Keuangan') && $this->proposal->histories->last()->status->value >= 2) || (user()->hasRole('Pimpinan') && $this->proposal->histories->last()->status->value >= 3)) {
            $this->verificationStatus = $history ? (int) $history->status->value : NULL;
            $this->notes              = $history ? $history->notes : NULL;
            $this->disable            = TRUE;
        }

        $this->voucher  = $this->proposal->voucher_no;
        $this->amount   = $this->proposal->amount;
        $this->approver = $this->proposal->approver_id;
        // dd($this->approver);
        $this->approvers = Configuration::where('group', 'approver')->get();
        $this->approvers = $this->approvers->map(function ($item, $key) {
            $value = json_decode($item['value']);
            return ['label' => $value[1], 'value' => $value[0], 'description' => 'Limit Rp. ' . number_format($item['name'], 2, ',', '.'), 'limit' => $item['name']];
        });
        if (user()->hasRole('Keuangan'))
            $this->statuses = [['label' => 'Terima', 'value' => 2], ['label' => 'Tolak', 'value' => 3]];
        if (user()->hasRole('Pimpinan'))
            $this->statuses = [['label' => 'Terima', 'value' => 4], ['label' => 'Tolak', 'value' => 5]];
    }

    public function render()
    {
        if ($this->disable)
            return view('livewire.proposal.verification-disable');
        else
            return view('livewire.proposal.verification');
    }

    public function save($status)
    {
        try {
            $this->proposal->save();
            ProposalHistory::create([
                'proposal_id' => $this->proposal->id,
                'user_id'     => user_id(),
                'status_id'   => $status,
                'notes'       => $this->notes
            ]);
            toast()->success('Data berhasil disimpan')->pushOnNextPage();
            return redirect()->route('verification.index');
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }

    public function verificate()
    {
        $status = Configuration::where('group', 'proposal status')->where('value', $this->verificationStatus)->first();

        $this->proposal->status_id    = $status->id;
        $this->proposal->amount       = $this->amount;
        $this->proposal->approver_id  = $this->approver;
        $this->proposal->account_code = $this->account;
        $this->save($status->id);

        if ($status->value == 2) {
            Notification::create([
                'context'     => 'verifikasi/proposal',
                'context_id'  => $this->proposal->id,
                'causer_id'   => $this->proposal->user_id,
                'description' => 'Proposal [nama] telah ' . strtolower($status->name),
                'user_id'     => $this->approver,
                'is_read'     => FALSE
            ]);
            Notification::create([
                'context'     => 'verifikasi/proposal',
                'context_id'  => $this->proposal->id,
                'causer_id'   => $this->proposal->user_id,
                'description' => 'Proposal ' . $this->proposal->name . ' telah ' . strtolower($status->name),
                'user_id'     => $this->proposal->user_id,
                'is_read'     => FALSE
            ]);
        } else if ($status->value == 4) {
            $kasir = UserModel::role('Kasir')->get();
            foreach ($kasir as $key => $value) {
                Notification::create([
                    'context'     => 'pencairan/proposal',
                    'context_id'  => $this->proposal->id,
                    'causer_id'   => $this->proposal->user_id,
                    'description' => 'Proposal [nama] telah ' . strtolower($status->name),
                    'user_id'     => $value->id,
                    'is_read'     => FALSE
                ]);
            }
            Notification::create([
                'context'     => 'verifikasi/proposal',
                'context_id'  => $this->proposal->id,
                'causer_id'   => $this->proposal->user_id,
                'description' => 'Proposal ' . $this->proposal->name . ' telah ' . strtolower($status->name),
                'user_id'     => $this->proposal->user_id,
                'is_read'     => FALSE
            ]);
        } else if ($status->value == 6) {
            Notification::create([
                'context'     => 'verifikasi/proposal',
                'context_id'  => $this->proposal->id,
                'causer_id'   => $this->proposal->user_id,
                'description' => 'Proposal ' . $this->proposal->name . ' dapat ' . strtolower($status->name) . ' segera hubungi kasir keuangan',
                'user_id'     => $this->proposal->user_id,
                'is_read'     => FALSE
            ]);
        }
    }

    public function withdrawal()
    {
        $status = Configuration::where('group', 'proposal status')->where('value', 6)->first();

        $this->proposal->status_id    = $status->id;
        $this->proposal->voucher_no   = $this->voucher;
        $this->save($status->id);
    }
}
