<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDbType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \DB::statement('ALTER TABLE blocked_companies ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE blocked_domains ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE cache ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE candidate_jobs ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE companies ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE company_jobs ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE failed_jobs ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE featured_jobs ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE homepage_logos ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE job_additional_skills ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE job_benefits ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE job_locations ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE job_required_skills ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE ledgers ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE master_data ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE migrations ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE model_has_permissions ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE model_has_roles ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE oauth_access_tokens ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE oauth_auth_codes ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE oauth_clients ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE oauth_personal_access_clients ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE oauth_refresh_tokens ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE password_histories ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE password_resets ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE payment_transactions ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE permissions ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE profile_view_transactions ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE reported_jobs ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE role_has_permissions ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE roles ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE sessions ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE system_configs ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE system_settings ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE user_preffered_data ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE user_work_profile_details ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE user_work_profiles ENGINE=InnoDB;');
        \DB::statement('ALTER TABLE users ENGINE=InnoDB;');
        \DB::statement('delete from user_work_profile_details where user_id not in (select id from users)');
        \DB::statement('delete from ledgers where user_id not in (select id from users)');
        \DB::statement('ALTER TABLE companies CHANGE size size varchar(255) NOT NULL AFTER location;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_settings', function (Blueprint $table) {
            //
        });
    }
}
