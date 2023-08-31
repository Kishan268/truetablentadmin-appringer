<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopupManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('duration')->default(0)->nullable();
            $table->text('img')->nullable();
            $table->string('button1_text')->nullable();
            $table->string('button1_action')->nullable();
            $table->string('button2_text')->nullable();
            $table->string('button2_action')->nullable();
            $table->string('type')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('popup_management');
    }
}
