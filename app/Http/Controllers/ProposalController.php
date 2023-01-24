<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Models\Configuration;

class ProposalController extends Controller
{
    protected $viewDirectory = 'pages.proposal.';

    public function create($category)
    {
        $this->data['page_title'] = ucfirst(__('route.proposal.create'));
        $this->data['mode']       = 'create';
        $this->data['category']   = Configuration::where('name', 'ilike', '%' . $category . '%')->first()->id;
        return $this->render('form');
    }

    public function show($proposal)
    {
        $this->data['page_title'] = ucfirst(__('route.proposal.show'));
        $this->data['proposal']   = Proposal::with(['histories', 'files'])->find($proposal);
        return $this->render(__FUNCTION__);
    }
}
