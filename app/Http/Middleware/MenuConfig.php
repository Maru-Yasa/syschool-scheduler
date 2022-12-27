<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuConfig
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
        $menus = [];
        $menuType = "guru";
        if(auth()->user()->role === 'admin'){
            $menuType = "admin";
        }
        $menus = config("menus.$menuType.menu");

        Event::listen(BuildingMenu::class, function (BuildingMenu $event) use ($menus) {
            foreach ($menus as $menu) {
                $event->menu->add($menu);
            }
        });

        return $next($request);
    }
}
