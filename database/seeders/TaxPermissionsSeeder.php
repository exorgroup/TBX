<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TaxPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'Tax_Read',
            'Tax_Create',
            'Tax_Update',
            'Tax_Delete',
            'Tax_Print'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'backpack']);
        }

        // Assign permissions to Administrator and BackOffice roles
        $adminRole = Role::where('name', 'Administrator')->first();
        $backOfficeRole = Role::where('name', 'BackOffice')->first();

        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        if ($backOfficeRole) {
            $backOfficeRole->givePermissionTo($permissions);
        }
    }
}
