<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserModel;
use App\Models\System\Role;
use Illuminate\Database\Seeder;
use App\Models\System\Permission;
use Illuminate\Support\Facades\DB;

class UserRolePermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::create(['name' => 'system role index']);
    Permission::create(['name' => 'system role add']);
    Permission::create(['name' => 'system role view']);
    Permission::create(['name' => 'system role edit']);
    Permission::create(['name' => 'system role delete']);

    Permission::create(['name' => 'system permission index']);
    Permission::create(['name' => 'system permission add']);
    Permission::create(['name' => 'system permission view']);
    Permission::create(['name' => 'system permission edit']);
    Permission::create(['name' => 'system permission delete']);

    Permission::create(['name' => 'system user index']);
    Permission::create(['name' => 'system user add']);
    Permission::create(['name' => 'system user view']);
    Permission::create(['name' => 'system user edit']);
    Permission::create(['name' => 'system user delete']);

    Permission::create(['name' => 'system tools index']);
    Permission::create(['name' => 'system tools add']);
    Permission::create(['name' => 'system tools view']);
    Permission::create(['name' => 'system tools edit']);
    Permission::create(['name' => 'system tools delete']);

    Permission::create(['name' => 'proposal index']);
    Permission::create(['name' => 'proposal add']);
    Permission::create(['name' => 'proposal view']);
    Permission::create(['name' => 'proposal edit']);
    Permission::create(['name' => 'proposal delete']);

    Permission::create(['name' => 'proposal report index']);
    Permission::create(['name' => 'proposal report add']);
    Permission::create(['name' => 'proposal report view']);
    Permission::create(['name' => 'proposal report edit']);
    Permission::create(['name' => 'proposal report delete']);

    Permission::create(['name' => 'verification index']);
    Permission::create(['name' => 'verification add']);
    Permission::create(['name' => 'verification view']);
    Permission::create(['name' => 'verification edit']);
    Permission::create(['name' => 'verification delete']);

    Permission::create(['name' => 'withdrawal index']);
    Permission::create(['name' => 'withdrawal add']);
    Permission::create(['name' => 'withdrawal view']);
    Permission::create(['name' => 'withdrawal edit']);
    Permission::create(['name' => 'withdrawal delete']);

    Permission::create(['name' => 'report index']);
    Permission::create(['name' => 'report add']);
    Permission::create(['name' => 'report view']);
    Permission::create(['name' => 'report edit']);
    Permission::create(['name' => 'report delete']);

    $SA = Role::create(['name' => 'SA']);

    $user = User::where('username', 'admin')->first();
    $users = UserModel::updateOrCreate([
      'id'        => $user->id,
      'name'      => $user->name,
      'full_name' => $user->full_name,
      'email'     => $user->email
  ]);
    $users->assignRole($SA);

    $keuangan = Role::create(['name' => 'Keuangan']);

    $keuangan->givePermissionTo('verification index');
    $keuangan->givePermissionTo('verification add');
    $keuangan->givePermissionTo('verification view');
    $keuangan->givePermissionTo('verification edit');
    $keuangan->givePermissionTo('verification delete');
    
    $keuangan->givePermissionTo('report index');
    $keuangan->givePermissionTo('report add');
    $keuangan->givePermissionTo('report view');
    $keuangan->givePermissionTo('report edit');
    $keuangan->givePermissionTo('report delete');

    $kasir = Role::create(['name' => 'Kasir']);

    $kasir->givePermissionTo('withdrawal index');
    $kasir->givePermissionTo('withdrawal add');
    $kasir->givePermissionTo('withdrawal view');
    $kasir->givePermissionTo('withdrawal edit');
    $kasir->givePermissionTo('withdrawal delete');
    
    $kasir->givePermissionTo('report index');
    $kasir->givePermissionTo('report add');
    $kasir->givePermissionTo('report view');
    $kasir->givePermissionTo('report edit');
    $kasir->givePermissionTo('report delete');

    $pimpinan = Role::create(['name' => 'Pimpinan']);

    $pimpinan->givePermissionTo('verification index');
    $pimpinan->givePermissionTo('verification add');
    $pimpinan->givePermissionTo('verification view');
    $pimpinan->givePermissionTo('verification edit');
    $pimpinan->givePermissionTo('verification delete');
    
    $pimpinan->givePermissionTo('report index');
    $pimpinan->givePermissionTo('report add');
    $pimpinan->givePermissionTo('report view');
    $pimpinan->givePermissionTo('report edit');
    $pimpinan->givePermissionTo('report delete');
    
    $yayasan = Role::create(['name' => 'Yayasan']);
    
    $yayasan->givePermissionTo('report index');
    $yayasan->givePermissionTo('report add');
    $yayasan->givePermissionTo('report view');
    $yayasan->givePermissionTo('report edit');
    $yayasan->givePermissionTo('report delete');

    $staf = Role::create(['name' => 'Staf']);

    $staf->givePermissionTo('proposal index');
    $staf->givePermissionTo('proposal add');
    $staf->givePermissionTo('proposal view');
    $staf->givePermissionTo('proposal edit');
    $staf->givePermissionTo('proposal delete');
  }
}
