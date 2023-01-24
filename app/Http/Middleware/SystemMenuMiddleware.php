<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\System\MenuItem;
use App\Models\System\Notification;

class SystemMenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $notifications = Notification::where('user_id', user_id())->where('is_read', FALSE)->get();
        view()->share('notifications', $notifications);

        $menu     = new MenuItem;
        $menuList = $menu->tree('menu');
        view()->share('menuList', $menuList);
        return $next($request);
    }
}
