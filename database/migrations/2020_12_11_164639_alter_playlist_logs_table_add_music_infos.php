<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlaylistLogsTableAddMusicInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlist_logs', function (Blueprint $table) {
            $table->renameColumn('track_id', 'music_track_id');
            $table->string('music_title')->nullable();
            $table->string('music_artist')->nullable();
            $table->string('music_artwork')->nullable();
            $table->string('music_url')->nullable();
            $table->string('music_itunes_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist_logs', function (Blueprint $table) {
            $table->renameColumn('music_track_id', 'track_id');
            $table->dropColumn('music_title');
            $table->dropColumn('music_artist');
            $table->dropColumn('music_artwork');
            $table->dropColumn('music_url');
            $table->dropColumn('music_itunes_url');
        });
    }
}
