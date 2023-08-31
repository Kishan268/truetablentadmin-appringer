<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    	$values = [
        	[
        		'name' => 'view_company_dashboard',
        		'module' => 'Company Dashboard',
        	],[
        		'name' => 'view_candidate_dashboard',
        		'module' => 'Candidate Dashboard',
        	],[
        		'name' => 'view_jobs_gigs_dashboard',
        		'module' => 'Jobs & Gigs Dashboard',
        	],[
        		'name' => 'view_company',
        		'module' => 'Company',
        	],[
        		'name' => 'add_company',
        		'module' => 'Company',
        	],[
        		'name' => 'update_company',
        		'module' => 'Company',
        	],[
        		'name' => 'delete_company',
        		'module' => 'Company',
        	],[
        		'name' => 'view_job',
        		'module' => 'Job',
        	],[
        		'name' => 'add_job',
        		'module' => 'Job',
        	],[
        		'name' => 'update_job',
        		'module' => 'Job',
        	],[
        		'name' => 'delete_job',
        		'module' => 'Job',
        	],[
        		'name' => 'view_reported_job',
        		'module' => 'Reported Job',
        	],[
        		'name' => 'view_gig',
        		'module' => 'Gig',
        	],[
        		'name' => 'add_gig',
        		'module' => 'Gig',
        	],[
        		'name' => 'update_gig',
        		'module' => 'Gig',
        	],[
        		'name' => 'delete_gig',
        		'module' => 'Gig',
        	],[
        		'name' => 'view_reported_gig',
        		'module' => 'Reported Gig',
        	],[
        		'name' => 'view_user',
        		'module' => 'User',
        	],[
        		'name' => 'add_user',
        		'module' => 'User',
        	],[
        		'name' => 'update_user',
        		'module' => 'User',
        	],[
        		'name' => 'delete_user',
        		'module' => 'User',
        	],[
        		'name' => 'view_referral',
        		'module' => 'Referral',
        	],[
        		'name' => 'add_referral',
        		'module' => 'Referral',
        	],[
        		'name' => 'update_referral',
        		'module' => 'Referral',
        	],[
                'name' => 'view_featured_job',
                'module' => 'Featured Job',
            ],[
                'name' => 'add_featured_job',
                'module' => 'Featured Job',
            ],[
                'name' => 'update_featured_job',
                'module' => 'Featured Job',
            ],[
                'name' => 'delete_featured_job',
                'module' => 'Featured Job',
            ],[
                'name' => 'view_featured_gigs',
                'module' => 'Featured Gigs',
            ],[
                'name' => 'add_featured_gigs',
                'module' => 'Featured Gigs',
            ],[
                'name' => 'update_featured_gigs',
                'module' => 'Featured Gigs',
            ],[
                'name' => 'delete_featured_gigs',
                'module' => 'Featured Gigs',
            ],[
                'name' => 'view_featured_logo',
                'module' => 'Featured Logo',
            ],[
                'name' => 'add_featured_logo',
                'module' => 'Featured Logo',
            ],[
                'name' => 'update_featured_logo',
                'module' => 'Featured Logo',
            ],[
                'name' => 'delete_featured_logo',
                'module' => 'Featured Logo',
            ],[
                'name' => 'view_roles_and_permissions',
                'module' => 'Roles And Permissions',
            ],[
                'name' => 'add_roles_and_permissions',
                'module' => 'Roles And Permissions',
            ],[
                'name' => 'update_roles_and_permissions',
                'module' => 'Roles And Permissions',
            ],[
                'name' => 'delete_roles_and_permissions',
                'module' => 'Roles And Permissions',
            ],[
                'name' => 'view_payment',
                'module' => 'Payment',
            ],[
                'name' => 'view_popups',
                'module' => 'Popups',
            ],[
                'name' => 'add_popups',
                'module' => 'Popups',
            ],[
                'name' => 'view_footer_content',
                'module' => 'Footer Content',
            ],[
                'name' => 'add_footer_content',
                'module' => 'Footer Content',
            ],
        	
        ];
        foreach ($values as $value) {
            Permission::create([
	        	'name' => $value['name'],
	        	'module' => $value['module']
	        ]);
        }
    }
}
