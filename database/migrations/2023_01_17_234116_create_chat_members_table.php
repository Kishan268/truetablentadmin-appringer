<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('last_message_seen_id')->nullable();
            $table->enum('status', ['report', 'block', 'unblock', 'report_block'])->nullable();
            $table->unsignedBigInteger('block_reason_id')->nullable();
            $table->text('block_reason_comment')->nullable();

            $table->enum('is_muted', ['0', '1'])->nullable();
            $table->enum('mute_duration', [1, 7, 30, -1])->nullable();
            $table->dateTime('muted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('last_message_seen_id')->references('id')->on('messages')->onDelete('cascade');
            $table->foreign('block_reason_id')->references('id')->on('master_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_members');
    }
}
