<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $viewDirectory = 'pages.verification.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.verification.index'));
        return $this->render(__FUNCTION__);
    }

    public function show($type, $proposal)
    {
        $this->data['page_title'] = ucfirst(__('route.verification.index'));
        $this->data['type']       = $type;
        $this->data['proposal']   = Proposal::with(['histories', 'files', 'report'])->find($proposal);
        return $this->render(__FUNCTION__);
    }
}
