<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCandidateIdNullableInProfileViewTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_view_transactions', function (Blueprint $table) {
            DB::statement('ALTER TABLE profile_view_transactions MODIFY candidate_id BIGINT(20) UNSIGNED NULL;');
            DB::statement('ALTER TABLE profile_view_transactions MODIFY company_user_id BIGINT(20) UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_view_transactions', function (Blueprint $table) {
            //
        });
    }
}
