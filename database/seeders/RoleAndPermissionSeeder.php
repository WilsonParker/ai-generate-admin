<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            'users' => [
                'manage-users',
                'show-users',
                'create-users',
                'edit-users',
                'delete-users',
            ],
            'stocks' => [
                'manage-stocks',
                'show-stocks',
                'create-stocks',
                'edit-stocks',
                'delete-stocks',
            ],
            'prompts' => [
                'manage-prompts',
                'show-prompt',
                'create-prompt',
                'edit-prompt',
                'delete-prompt',
            ],
            'mails' => [
                'manage-mails',
                'show-mail',
                'create-mail',
                'edit-mail',
                'delete-mail',
            ],
            'blogs' => [
                'manage-blog-posts',
                'show-blog-posts',
                'create-blog-posts',
                'edit-blog-posts',
                'delete-blog-posts',
            ],
            'enterprise' => [
                'manage-enterprise',
                'show-enterprise',
                'create-enterprise',
                'edit-enterprise',
                'delete-enterprise',
            ]
        ]);

        $permissions->flatten(1)->each(function ($permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        });

        $adminRole = Role::createOrFirst(['name' => 'Admin']);
        $editorRole = Role::createOrFirst(['name' => 'Blog Editor']);

        $adminRole->givePermissionTo(array_merge(
            $permissions['users'],
            $permissions['stocks'],
            $permissions['prompts'],
            $permissions['mails'],
            $permissions['blogs'],
            $permissions['enterprise'],
        ));

        $editorRole->givePermissionTo($permissions['blogs']);
    }
}
