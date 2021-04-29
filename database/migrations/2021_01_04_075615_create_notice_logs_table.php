<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from_user_id');
            $table->unsignedBigInteger('to_user_id');
            $table->unsignedTinyInteger('action_type');
            $table->unsignedBigInteger('target_reply_post_id')->nullable();
            $table->unsignedBigInteger('target_post_id')->nullable();
            $table->unsignedBigInteger('target_playlist_id')->nullable();
            $table->boolean('read');
            $table->timestamps();
            $table->foreign('from_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('target_reply_post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('target_post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('target_playlist_id')->references('id')->on('playlists')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notice_logs');
    }
}
