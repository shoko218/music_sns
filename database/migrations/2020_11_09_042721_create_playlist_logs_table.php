<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('playlist_id');
            $table->unsignedBigInteger('track_id');
            $table->timestamps();
            $table->foreign('playlist_id')->references('id')->on('playlists')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_logs');
    }
}
