<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{

    public function run()
    {

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();


        $permissions = [
            'view_accounts_tree', 'manage_accounts',
            'create_journal_entry', 'view_journal_entries', 'manage_cost_centers', 'view_journals', 'view_account_statement',
            'view_invoices', 'create_sales_invoice', 'create_sales_return', 'create_purchase_invoice', 'create_purchase_return',
            'manage_products', 'manage_categories', 'manage_customers',
            'view_trial_balance', 'view_income_statement', 'view_balance_sheet',
            'manage_employees', 'manage_leaves', 'manage_overtimes', 'manage_deductions', 'manage_payrolls', 'manage_hr_categories', 'manage_loans',
            'manage_suppliers', 'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


        $superAdmin = Role::create(['name' => 'super_admin']);
        $companyAdmin = Role::create(['name' => 'company_admin']);
        $branchUser = Role::create(['name' => 'branch_user']);


        $superAdmin->syncPermissions($permissions);


        $companyAdminPermissions = [
            'view_accounts_tree', 'manage_accounts',
            'create_journal_entry', 'view_journal_entries', 'manage_cost_centers', 'view_journals', 'view_account_statement',
            'view_invoices', 'create_sales_invoice', 'create_sales_return', 'create_purchase_invoice', 'create_purchase_return',
            'manage_products', 'manage_categories', 'manage_customers',
            'view_trial_balance', 'view_income_statement', 'view_balance_sheet',
            'manage_employees', 'manage_leaves', 'manage_overtimes', 'manage_deductions', 'manage_payrolls', 'manage_hr_categories', 'manage_loans',
            'manage_suppliers',
        ];
        $companyAdmin->syncPermissions($companyAdminPermissions);


        $branchUserPermissions = [
            'view_journal_entries', 'view_invoices', 'create_sales_invoice', 'create_purchase_invoice',
            'view_account_statement', 'manage_products', 'manage_customers',
        ];
        $branchUser->syncPermissions($branchUserPermissions);
    }
}
