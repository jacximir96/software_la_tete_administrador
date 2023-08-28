<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions_array = [];
        array_push($permissions_array,Permission::create(['name' => 'crear_usuarios']));
        array_push($permissions_array,Permission::create(['name' => 'editar_usuarios']));
        array_push($permissions_array,Permission::create(['name' => 'eliminar_usuarios']));
        $viewUsuariosPermission = Permission::create(['name' => 'mirar_usuarios']);
        array_push($permissions_array);

        $administradorRole = Role::create(['name' => 'administrador','estado_rol' => 1]);
        $administradorRole->syncPermissions($permissions_array);/* sincronizar varios permisos con un rol */

        $viewUsuariosRole = Role::create(['name' => 'usuario','estado_rol' => 1]);
        $viewUsuariosRole->givePermissionTo($viewUsuariosPermission);/* asignar un permiso a un rol */

        /* Crear usuarios mediante laravel */
        $userAdministrador= User::create([
            'name' => 'administrador',
            'email' => 'administrador@inr.gob.pe',
            'password' => Hash::make('12345678'),
        ]);

        $userAdministrador->assignRole('administrador');/* Asignar rol a usuario */

        $userViewUsuarios= User::create([
            'name' => 'jacximir',
            'email' => 'jacximir96@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $userViewUsuarios->assignRole('usuario');/* Asignar rol a usuario */

        User::create([
            'name' => 'usuario',
            'email' => 'usuario@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

    }
}
