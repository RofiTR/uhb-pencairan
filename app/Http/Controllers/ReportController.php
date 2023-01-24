<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $viewDirectory = 'pages.proposal.report.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.report.index'));
        return $this->render(__FUNCTION__);
    }

    public function show($proposal)
    {
        $this->data['page_title'] = ucfirst(__('route.report.index'));
        $this->data['proposal']   = $proposal;
        return $this->render(__FUNCTION__);
    }
}
