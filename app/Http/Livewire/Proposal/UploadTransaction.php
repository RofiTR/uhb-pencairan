<?php

namespace App\Http\Livewire\Proposal;

use App\Models\File;
use Livewire\Component;
use App\Models\Proposal;
use WireUi\Traits\Actions;
use App\Models\Configuration;
use App\Models\TransferHistory;
use Livewire\WithFileUploads;

class UploadTransaction extends Component
{
    use Actions, WithFileUploads;

    public $uploadModal, $view = 'list', $proposal, $transactions, $fileName, $file, $banks, $bank, $amount, $accountName, $bankName, $accountNumber, $limit = 0;

    protected $listeners = [
        'proposal' => 'setProposal',
        'view'     => 'setView'
    ];

    protected $rules = [
        'file' => 'required'
    ];

    public function setView($view)
    {
        $this->view = $view;
    }

    public function setProposal($id)
    {
        $this->proposal      = Proposal::find($id);
        $this->banks         = collect(Configuration::where('group', 'bank')->get()->toArray());
        $this->bankName      = $this->proposal->withdrawal['bank'];
        $this->accountName   = $this->proposal->withdrawal['account_name'];;
        $this->accountNumber = $this->proposal->withdrawal['account_number'];
        $this->view          = 'list';

        $this->transactions = TransferHistory::where('proposal_id', $id)->get();
        $this->amount       = $this->transactions ? $this->proposal->amount - $this->transactions->sum('out') : $this->proposal->amount;
        $this->limit        = $this->amount;

        $this->resetValidation();
    }

    public function updatedAmount($value)
    {
        $amount = str_replace('.', '', $value);
        $this->validate(
            ['amount' => 'lte:' . $this->limit,],
            [
                'amount.lte' => 'The :attribute must be less than or equal to ' . number_format($this->limit, 0, ',', '.'),
            ],
            ['amount' => 'Nominal Transfer']
        );
    }

    public function render()
    {
        return view('livewire.proposal.upload-transaction');
    }

    public function save()
    {
        $this->validate(
            [
                'amount' => 'lte:' . $this->limit,
                'bank'   => 'required',
                'file'   => 'required'
            ],
            [
                'amount.lte' => 'The :attribute must be less than or equal to ' . number_format($this->limit, 0, ',', '.'),
                'bank'       => 'The :attribute is required',
                'file'       => 'The :attribute is required'
            ],
            [
                'amount' => 'Nominal Transfer',
                'bank'   => 'Rekening Sumber',
                'file'   => 'File'
            ]
        );

        try {
            $this->view = 'list';
            $bank       = $this->banks->where('id', $this->bank)->first();

            $transaction              = new TransferHistory();
            $transaction->proposal_id = $this->proposal->id;
            $transaction->from        = [
                'id'            => $bank['id'],
                'bank'          => $bank['name'],
                'acount_number' => $bank['value'],
            ];
            $transaction->to = [
                'bank'          => $this->bankName,
                'acount_name'   => $this->accountName,
                'acount_number' => $this->accountNumber,
            ];
            $transaction->in  = 0;
            $transaction->out = str_replace('.', '', $this->amount);
            $transaction->save();

            $this->file->store('files', 'public');
            File::create([
                'context'    => 'App\Models\TransferHistory',
                'context_id' => $transaction->id,
                'name'       => $this->fileName,
                'file_path'  => 'files',
                'file_name'  => $this->file->hashName(),
                'file_size'  => $this->file->getSize(),
                'mime_type'  => $this->file->extension(),
                'sort_order' => 1,
                'user_id'    => user_id(),
            ]);

            $this->notification([
                'title'       => NULL,
                'description' => 'Bukti transfer berhasil disimpan',
                'icon'        => 'success'
            ]);

            $this->dispatchBrowserEvent('close-modal');
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
