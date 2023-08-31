<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateNotificationSettingsTable extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('subject')->nullable();
            $table->text('mail_body')->nullable();
            $table->text('sms_body')->nullable();
            $table->text('wa_body')->nullable();
            $table->enum('is_mail_enabled',['0','1'])->default('0');
            $table->enum('is_sms_enabled',['0','1'])->default('0');
            $table->enum('is_wa_enabled',['0','1'])->default('0');
            $table->string('variables')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('deleted_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_settings');
    }
}
