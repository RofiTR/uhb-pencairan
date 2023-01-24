<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    protected $viewDirectory = 'pages.withdrawal.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.withdrawal.index'));
        return $this->render(__FUNCTION__);
    }

    public function show($proposal)
    {
        $this->data['page_title'] = ucfirst(__('route.withdrawal.show'));
        $this->data['proposal']   = Proposal::with(['histories', 'files', 'report'])->find($proposal);
        return $this->render(__FUNCTION__);
    }
}
