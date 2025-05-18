<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{


    public function run()
    {
        // Clear cached permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $companyAdminPermissions = [
            'company_view_accounts_tree', 'company_manage_accounts',
            'company_create_accounts','company_cmanage_edit_account','company_cmanage_delete_account',

            'company_create_journal_entry', 'company_view_journal_entries', 'company_edit_journal_entries', 'company_delete_journal_entries',
            'company_manage_cost_centers', 'company_view_journals', 'company_view_account_statement',

            'company_view_invoices', 'company_create_sales_invoice', 'company_create_sales_return', 'company_create_purchase_invoice', 'company_create_purchase_return',
            'company_manage_products', 'company_manage_categories', 'company_manage_customers',

            'company_view_trial_balance', 'company_view_income_statement', 'company_view_balance_sheet',

            'company_manage_employees', 'company_manage_leaves', 'company_manage_overtimes', 'company_manage_deductions', 'company_manage_payrolls', 'company_manage_hr_categories', 'company_manage_loans',
            'company_manage_suppliers', 'company_manage_settings',
            'company_manage_company_information','company_manage_backup','company_manage_years','comapny_manage_users',
            'company_link_account_customers','company_link_account_suppliers','company_link_account_cach_register',

        ];

        $permissions = [
            'view_accounts_tree', 'manage_accounts',
            'create_journal_entry', 'view_journal_entries', 'manage_cost_centers', 'view_journals', 'view_account_statement',
            'view_invoices', 'create_sales_invoice', 'create_sales_return', 'create_purchase_invoice', 'create_purchase_return',
            'manage_products', 'manage_categories', 'manage_customers',
            'view_trial_balance', 'view_income_statement', 'view_balance_sheet',
            'manage_employees', 'manage_leaves', 'manage_overtimes', 'manage_deductions', 'manage_payrolls', 'manage_hr_categories', 'manage_loans',
            'manage_suppliers',
        ];

        foreach ($companyAdminPermissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }


        // Create or update roles
        $superAdmin = Role::updateOrCreate(['name' => 'super_admin']);
        $companyAdmin = Role::updateOrCreate(['name' => 'company_admin']);
        $branchUser = Role::updateOrCreate(['name' => 'branch_user']);

        // Assign permissions to roles
        $superAdmin->syncPermissions($permissions);
        $companyAdmin->syncPermissions($companyAdminPermissions);

        $branchUserPermissions = [
            'view_journal_entries', 'view_invoices', 'create_sales_invoice', 'create_purchase_invoice',
            'view_account_statement', 'manage_products', 'manage_customers',
        ];
        $branchUser->syncPermissions($branchUserPermissions);
    }
}
