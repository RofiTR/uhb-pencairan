<?php

namespace Database\Seeders;

use App\Models\System\Menu;
use App\Models\System\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::create([
            'name' => 'menu'
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => null,
            'name'       => 'Dashboard',
            'permission' => null,
            'label'      => 'Dashboard',
            'route'      => 'dashboard.index',
            'url'        => 'dashboard',
            'order'      => 1
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => null,
            'name'       => 'Verifikasi',
            'permission' => ['verification.index'],
            'label'      => 'Verifikasi',
            'route'      => 'verification.index',
            'url'        => 'verifikasi',
            'order'      => 2
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => null,
            'name'       => 'Pencairan',
            'permission' => ['withdrawal.index'],
            'label'      => 'Pencairan',
            'route'      => 'withdrawal.index',
            'url'        => 'pencairan',
            'order'      => 3
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => null,
            'name'       => 'LPJ',
            'permission' => ['proposal.report.index'],
            'label'      => 'LPJ',
            'route'      => 'sppd.index',
            'url'        => 'lpj',
            'order'      => 4
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => null,
            'name'       => 'Laporan',
            'permission' => ['report.index'],
            'label'      => 'Laporan',
            'route'      => 'report.index',
            'url'        => 'laporan',
            'order'      => 5
        ]);

        $system = MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => null,
            'name'       => 'Sistem',
            'permission' => ['system.role.index', 'system.permission.index', 'system.user.index', 'system.tools.index'],
            'label'      => 'Sistem',
            'route'      => 'system.index',
            'url'        => 'system',
            'order'      => 6
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => $system->id,
            'name'       => 'Role',
            'permission' => ['system.role.index'],
            'label'      => 'Role',
            'route'      => 'role.index',
            'url'        => 'role',
            'order'      => 1
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => $system->id,
            'name'       => 'Permission',
            'permission' => ['system.permission.index'],
            'label'      => 'Permission',
            'route'      => 'permission.index',
            'url'        => 'permission',
            'order'      => 2
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => $system->id,
            'name'       => 'User',
            'permission' => ['system.user.index'],
            'label'      => 'User',
            'route'      => 'user.index',
            'url'        => 'user',
            'order'      => 3
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => $system->id,
            'name'       => 'Pengaturan',
            'permission' => ['system.configuration.index'],
            'label'      => 'Pengaturan',
            'route'      => 'configuration.index',
            'url'        => 'pengaturan',
            'order'      => 4
        ]);

        MenuItem::create([
            'menu_id'    => $menu->id,
            'parent_id'  => $system->id,
            'name'       => 'Tools',
            'permission' => ['system.tools.index'],
            'label'      => 'Tools',
            'route'      => 'tools.index',
            'url'        => 'tools',
            'order'      => 5
        ]);
    }
}
