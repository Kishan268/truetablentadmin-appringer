<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastDeletedMessageIdInChatMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_members', function (Blueprint $table) {
            $table->unsignedBigInteger('last_message_deleted_id')->nullable()->after('last_message_seen_id');
            $table->foreign('last_message_deleted_id')->references('id')->on('messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_members', function (Blueprint $table) {
            //
        });
    }
}
