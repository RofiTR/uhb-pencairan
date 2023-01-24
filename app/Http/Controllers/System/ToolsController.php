<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    protected $viewDirectory = 'pages.system.tools.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.system.tools.index'));
        return $this->render(__FUNCTION__);
    }
}
