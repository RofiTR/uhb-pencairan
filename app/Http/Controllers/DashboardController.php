<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $viewDirectory = 'pages.dashboard.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.dashboard.index'));
        return $this->render(__FUNCTION__);
    }
}
