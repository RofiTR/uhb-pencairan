<?php

namespace App\Http\Livewire\Proposal;

use App\Models\File;
use App\Models\User;
use Livewire\Component;
use App\Models\Proposal;
use WireUi\Traits\Actions;
use App\Models\Configuration;
use Livewire\WithFileUploads;
use App\Models\ProposalReport;
use App\Models\ProposalHistory;
use App\Models\ProposalReportSppd;

class Lpj extends Component
{
    use Actions, WithFileUploads;

    public $proposal, $category, $types, $approvers, $statuses, $type, $name, $amount = 0, $description, $withdrawal, $approver;
    public $fileNames = [], $files = [], $sppd = [], $destinations = [], $departures = [], $arrives = [], $members = [];
    public $inputFiles = [], $inputSppd = [];
    public $i = 1, $j = 1;
    public $users;

    public function mount($proposal)
    {
        $this->proposal  = $proposal;

        $this->category  = Configuration::find($this->proposal->category_id);
        $this->type      = Configuration::find($this->proposal->type_id);
        $this->approvers = Configuration::where('group', 'approver')->get();
        $this->approvers = $this->approvers->map(function ($item, $key) {
            $value = json_decode($item['value']);
            return ['label' => $value[1], 'value' => $value[0], 'description' => 'Limit Rp. ' . number_format($item['name'], 2, ',', '.'), 'limit' => $item['name']];
        });
        $this->users = User::get()->toArray();
    }

    public function addSppd($i)
    {
        $i = $i + 1;
        $this->j = $i;
        array_push($this->inputSppd, $i);
    }

    public function removeSppd($i)
    {
        // if (isset($this->file_ids[$i])) {
        //     $variety = File::find($this->file_ids[$i]);
        //     $variety->forceDelete();
        //     unset($this->variety[$i]);
        // }
        unset($this->inputSppd[$i]);
    }

    public function addAttachment($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputFiles, $i);
    }

    public function removeAttachment($i)
    {
        if (isset($this->file_ids[$i])) {
            $variety = File::find($this->file_ids[$i]);
            $variety->forceDelete();
            unset($this->variety[$i]);
        }
        unset($this->inputFiles[$i]);
    }

    public function render()
    {
        return view('livewire.proposal.lpj');
    }

    public function save()
    {
        dd($this->destinations, $this->departures, $this->arrives, $this->members);
        try {
            $lpj              = new ProposalReport();
            $lpj->proposal_id = $this->proposal->id;
            $lpj->type_id     = $this->proposal->type_id;
            $lpj->category_id = $this->proposal->category_id;
            $lpj->sppd        = ($this->type->value == 1) ? TRUE : FALSE;
            $lpj->description = $this->description;
            $lpj->approver_id = $this->approver;

            $status         = Configuration::where('group', 'report status')->where('value', '1')->first()->id;
            $lpj->status_id = $status;
            $lpj->user_id   = user_id();
            $lpj->save();
            
            $order = 1;
            foreach ($this->files as $index => $file) {
                $file->store('files', 'public');
                File::create([
                    'context'    => 'App\Models\ProposalReport',
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

            if ($this->type->value == 1) {
                foreach ($this->sppd as $index => $value) {
                    $sppd                     = new ProposalReportSppd();
                    $sppd->proposal_report_id = $lpj->id;
                    $sppd->area               = $this->destinations[$index];
                    $sppd->departure          = $this->departures[$index];
                    $sppd->arrive             = $this->arrives[$index];
                    $sppd->save();
                }
            }
            
            ProposalHistory::create([
                'proposal_id' => $lpj->id,
                'user_id'     => user_id(),
                'status_id'   => $status
            ]);
            toast()->success('Data berhasil disimpan')->push();
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
