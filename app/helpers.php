<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

if (!function_exists('user_id')) {
  function user_id()
  {
    return Auth::id();
  }
}

if (!function_exists('user')) {
  function user()
  {
    return Auth::user();
  }
}

if (!function_exists('feeder')) {
  function feeder($act = "", $data = [])
  {
    if ($act == 'GetToken')
      return Http::acceptJson()->asForm()->post(config('feeder.feeder_url') . '?=&=', [
        'act'      => $act,
        'username' => config('feeder.feeder_username'),
        'password' => config('feeder.feeder_password')
      ]);
    $data = array_merge(["act" => $act, "token" => session("token")], $data);
    return Http::acceptJson()->asForm()->post(config('feeder.feeder_url'), $data)->collect('data');
  }
}

if (!function_exists('trans_route')) {
  function trans_route($route)
  {
    return str_replace(' ', '-', __($route));
  }
}
