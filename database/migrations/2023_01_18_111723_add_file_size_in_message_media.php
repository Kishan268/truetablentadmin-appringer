<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileSizeInMessageMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_media', function (Blueprint $table) {
            $table->string('original_name', 100)->after('type');
            $table->float('size_mb', 10, 4)->default(0.0)->after('original_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_media', function (Blueprint $table) {
            //
        });
    }
}
