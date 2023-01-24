<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $viewDirectory = 'pages.system.permission.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.system.permission.index'));
        return $this->render(__FUNCTION__);
    }
}
