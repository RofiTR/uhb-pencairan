<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    protected $viewDirectory = 'pages.system.configuration.';

    public function index()
    {
        $this->data['page_title'] = ucwords(__('route.system.configuration.index'));
        return $this->render(__FUNCTION__);
    }
}
