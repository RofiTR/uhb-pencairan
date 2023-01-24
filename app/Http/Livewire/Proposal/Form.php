<?php

namespace App\Http\Livewire\Proposal;

use App\Models\File;
use Livewire\Component;
use App\Models\Proposal;
use WireUi\Traits\Actions;
use Illuminate\Support\Str;
use App\Models\Configuration;
use Livewire\WithFileUploads;
use App\Models\ProposalHistory;
use App\Models\System\Notification;
use App\Models\UserModel;

class Form extends Component
{
    use Actions, WithFileUploads;

    public $mode, $category, $proposal, $types, $approvers, $statuses, $type, $name, $amount = 0, $description, $withdrawal, $approver;
    public $fileNames = [], $files = [];
    public $banks = [], $bank, $accountName, $accountAlias, $bankName, $accountNumber, $saveAccount, $limit;
    public $inputs = [];
    public $i = 1;

    protected $rules = [
        'type'       => 'required',
        'name'       => 'required',
        'amount'     => 'required',
        'withdrawal' => 'required',
        'files.*'    => 'mimes:jpg,png,bmp,pdf|max:2048'
    ];

    protected $validationAttributes = [
        'files.*' => 'attachment'
    ];

    public function mount($mode = NULL, $category = NULL, $proposal = NULL)
    {
        $this->mode     = $mode;
        $this->category = Configuration::find($category);
        $this->proposal = new Proposal();
        if ($proposal) {
            $this->proposal = $proposal;
        }
        $this->types     = collect(Configuration::where('group', 'type')->get()->toArray());
        $this->approvers = Configuration::where('group', 'approver')->get();
        $this->approvers = $this->approvers->map(function ($item, $key) {
            $value = json_decode($item['value']);
            return ['label' => $value[1], 'value' => $value[0], 'description' => 'Limit Rp. ' . number_format($item['name'], 2, ',', '.'), 'limit' => $item['name']];
        });
        $this->limit = user()->limit;
        if (user()->bank_account) {
            $this->banks = collect(user()->bank_account)->map(function ($item, $key) {
                return ['label' => $item['name'] . ' / ' . $item['alias'], 'value' => $key, 'description' => $item['bank'] . ' : ' . $item['number'], 'detail' => $item];
            });
        }
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
    }

    public function remove($i)
    {
        if (isset($this->file_ids[$i])) {
            $variety = File::find($this->file_ids[$i]);
            $variety->forceDelete();
            unset($this->variety[$i]);
        }
        unset($this->inputs[$i]);
    }

    public function render()
    {
        return view('livewire.proposal.form');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedAmount($value)
    {
        $this->approver = $this->approvers->firstWhere('limit', '>=', str_replace('.', '', $value)) ?? $this->approvers->last();
        $this->approver = $this->approver['value'];
    }

    public function updatedBank($value)
    {
        $bank                = $this->banks[$value]['detail'];
        $this->accountName   = $bank['name'];
        $this->accountAlias  = $bank['alias'];
        $this->bankName      = $bank['bank'];
        $this->accountNumber = $bank['number'];
    }

    public function updatedFiles()
    {
        $this->validate([
            'files.*' => 'mimes:jpg,png,bmp,pdf|max:2048',
        ]);
    }

    public function save()
    {
        if ($this->saveAccount) {
            $banks = user()->bank_account;
            array_push($banks, [
                'name'   => $this->accountName,
                'alias'  => $this->accountAlias,
                'bank'   => $this->bankName,
                'number' => $this->accountNumber,
            ]);
            UserModel::where('id', user_id())->update(['bank_account' => $banks]);
        }

        $validatedData = $this->validate();

        $this->proposal->category_id = $this->category->id;
        $this->proposal->sppd        = $this->types->where('id', $this->type)->first()['name'] == 'Akademik' ? TRUE : FALSE;
        $this->proposal->type_id     = $validatedData['type'];
        $this->proposal->name        = $validatedData['name'];
        $this->proposal->description = $this->description;
        $this->proposal->amount      = str_replace('.', '', $validatedData['amount']);
        $this->proposal->withdrawal  = [
            'method'         => $validatedData['withdrawal'],
            'bank'           => $this->bankName,
            'account_name'   => $this->accountName,
            'account_alias'  => $this->accountAlias,
            'account_number' => str_replace(' ', '', $this->accountNumber),
        ];
        $this->proposal->approver_id = $this->approver;
        $this->proposal->user_id     = user_id();

        if ($this->mode == 'create') {
            $status = Configuration::where('group', 'proposal status')->where('value', '1')->first()->id;
            $this->proposal->status_id = $status;
        }

        try {
            $this->proposal->save();

            $order = 1;
            foreach ($this->files as $index => $file) {
                $file->store('files', 'public');
                File::create([
                    'context'    => 'App\Models\Proposal',
                    'context_id' => $this->proposal->id,
                    'name'       => $this->fileNames[$index],
                    'file_path'  => 'files',
                    'file_name'  => $file->hashName(),
                    'file_size'  => $file->getSize(),
                    'mime_type'  => $file->extension(),
                    'sort_order' => $order++,
                    'user_id'    => user_id(),
                ]);
            }

            $this->limit[$this->category->name] += 1;
            UserModel::where('id', user_id())->update(['limit' => $this->limit]);

            ProposalHistory::create([
                'proposal_id' => $this->proposal->id,
                'user_id'     => user_id(),
                'status_id'   => $status
            ]);

            $keuangan = UserModel::role('Keuangan')->get();
            foreach ($keuangan as $key => $value) {
                Notification::create([
                    'context'     => 'verifikasi/proposal',
                    'context_id'  => $this->proposal->id,
                    'causer_id'   => user_id(),
                    'description' => '[nama] mengajukan proposal pencairan baru.',
                    'user_id'     => $value->id,
                    'is_read'     => FALSE
                ]);
            }
            toast()->success('Data berhasil disimpan')->pushOnNextPage();
            return redirect()->route('dashboard.index');
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
