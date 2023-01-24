<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $viewDirectory = 'pages.system.role.';

    public function index()
    {
        $this->data['page_title'] = ucfirst(__('route.system.role.index'));
        return $this->render(__FUNCTION__);
    }
}
