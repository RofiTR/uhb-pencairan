<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $viewDirectory = 'pages.system.user.';

    public function index()
    {
        $this->data['page_title'] = ucwords(__('route.system.user.index'));
        return $this->render(__FUNCTION__);
    }

    public function show($user)
    {
        $this->data['page_title'] = ucwords(__('route.system.user.show'));
        $this->data['user']       = $user;
        return $this->render('form');
    }
}
