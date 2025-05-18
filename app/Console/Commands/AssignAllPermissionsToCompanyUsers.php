<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

//use Spatie\Permission\Models\Permission;

class AssignAllPermissionsToCompanyUsers extends Command
{
    protected $signature = 'permissions:assign-to-all-users';
    protected $description = 'Assign all permissions to all users in the system';

    public function handle()
    {
        $permissions = Permission::pluck('name')->toArray();

        if (empty($permissions)) {
            $this->error('❌ لا توجد صلاحيات في النظام.');
            return 1;
        }

        $users = User::where('model_type' , 'COMPANY')
            ->get();

        if ($users->isEmpty()) {
            $this->warn('⚠️ لا يوجد مستخدمون في النظام.');
            return 0;
        }

        foreach ($users as $user) {
            $user->givePermissionTo($permissions);
            $this->info("✅ تم إعطاء جميع الصلاحيات للمستخدم: {$user->email}");
        }

        $this->info('🎉ok all done .');
        return 0;
    }
}
