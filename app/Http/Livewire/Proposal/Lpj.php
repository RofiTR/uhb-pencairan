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
use Illuminate\Support\Facades\DB;
use App\Models\ProposalReportSppdMember;

class Lpj extends Component
{
    use Actions, WithFileUploads;

    public $proposal, $type, $description, $addModal;
    public $fileNames = [], $files = [], $sppd = [], $destinations = [], $departures = [], $arrives = [], $members = [];
    public $fdestinations, $fdepartures, $farrives, $fmembers;
    public $inputFiles = [], $inputSppd = [];
    public $i = 1, $j = 1;
    public $users;

    public function mount($proposal)
    {
        $this->proposal = $proposal;
        $this->users    = User::get()->toArray();
        $this->type     = Configuration::find($this->proposal->type_id);
    }

    public function addSppd($i)
    {
        $i = $i + 1;
        $this->j = $i;
        array_push($this->inputSppd, $i);
        array_push($this->destinations, $this->fdestinations);
        array_push($this->departures, $this->fdepartures);
        array_push($this->arrives, $this->farrives);
        array_push($this->members, $this->fmembers);
        $this->fdestinations = '';
        $this->fdepartures   = '';
        $this->farrives      = '';
        $this->fmembers      = '';
    }

    public function removeSppd($i)
    {
        unset($this->inputSppd[$i]);
        unset($this->destinations[$i]);
        unset($this->departures[$i]);
        unset($this->arrives[$i]);
        unset($this->members[$i]);
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
        try {
            DB::transaction(function () {
                $lpj              = new ProposalReport();
                $lpj->proposal_id = $this->proposal->id;
                $lpj->type_id     = $this->proposal->type_id;
                $lpj->category_id = $this->proposal->category_id;
                $lpj->sppd        = ($this->type->value == 1) ? TRUE : FALSE;
                $lpj->withdrawal  = $this->proposal->amount;
                $lpj->realization = 0;
                $lpj->saldo       = 0;
                $lpj->description = $this->description;

                $status         = Configuration::where('group', 'report status')->where('value', '1')->first()->id;
                $lpj->status_id = $status;
                $lpj->user_id   = user_id();
                $lpj->save();

                $order = 1;
                foreach ($this->files as $index => $file) {
                    $file->store('files', 'public');
                    File::create([
                        'context'    => 'App\Models\ProposalReport',
                        'context_id' => $lpj->id,
                        'name'       => $this->fileNames[$index],
                        'file_path'  => 'files',
                        'file_name'  => $file->hashName(),
                        'file_size'  => $file->getSize(),
                        'mime_type'  => $file->extension(),
                        'sort_order' => $order++,
                        'user_id'    => user_id(),
                    ]);
                }

                ProposalHistory::create([
                    'proposal_id' => $lpj->id,
                    'user_id'     => user_id(),
                    'status_id'   => $status
                ]);

                if ($this->type->value == 1) {
                    foreach ($this->inputSppd as $index => $value) {
                        $sppd                     = new ProposalReportSppd();
                        $sppd->proposal_report_id = $lpj->id;
                        $sppd->destination        = $this->destinations[$index];
                        $sppd->departure          = $this->departures[$index];
                        $sppd->arrive             = $this->arrives[$index];
                        $sppd->save();
                        // $members = explode(',', $this->members[$index]);
                        foreach ($this->members[$index] as $member) {
                            ProposalReportSppdMember::create([
                                'proposal_report_sppd_id' => $sppd->id,
                                'user_id'                 => $member,
                            ]);
                        }
                    }
                }
            });
            $this->proposal = Proposal::with(['histories', 'files', 'report'])->find($this->proposal->id);

            $this->notification([
                'title'       => NULL,
                'description' => 'Data berhasil disimpan',
                'icon'        => 'success'
            ]);
        } catch (\Exception $e) {
            $this->notification([
                'title'       => NULL,
                'description' => $e->getMessage(),
                'icon'        => 'error'
            ]);
        }
    }
}
