<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Domains\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SsoController extends Controller
{
    public function index(Request $request)
    {
        if (user()) {
            return redirect()->route('student.dashboard.index');
        } else {
            if ($request->query('q'))
                return view('layouts.guest');

            $prev_url = url()->previous();
            if (str_contains(config('sso.url'), config('sso.base_url')))
                $prev_url = config('app.url');
            return redirect()->away(config('sso.url') . '/authorize?service=' . urlencode(config('app.url')) . '&key=' . config('sso.public_key') . '&redirect=' . urlencode($prev_url));
        }
    }

    public function store($encrypted, Request $request)
    {
        $redirect = $request->query('redirect');
        $newEncrypter = new \Illuminate\Encryption\Encrypter(substr(config('sso.private_key'), 0, 16) . substr(config('sso.private_key'), 100, 16), Config::get('app.cipher'));
        $decrypted = $newEncrypter->decrypt($encrypted);
        $user_id = DB::connection('dapo')->table('sys_sessions')->select('user_id')->where('id', $decrypted)->get()[0]->user_id;
        $user = User::find($user_id);
        try {
            $user = UserModel::updateOrCreate([
                'id'        => $user->id,
                'name'      => $user->name,
                'full_name' => $user->full_name,
                'email'     => $user->email
            ]);
        } catch (\Exception $e) {
            return redirect()->away(config('sso.base_url'));
        }
        Auth::loginUsingId($user_id);

        event(new Login(user()));

        $service_id = DB::connection('dapo')->table('services')->select('id')->where('public_key', config('sso.public_key'))->get()[0]->id;
        session()->flash('ses_id', [
            'id'=>$decrypted,
            'service'=>$service_id
        ]);

        return redirect()->away($redirect);
    }

    public function destroy()
    {
        return redirect()->away(config('sso.url') . '/logout?service=' . urlencode(config('app.url')) . '&key=' . config('app.public_key'));
    }
}
