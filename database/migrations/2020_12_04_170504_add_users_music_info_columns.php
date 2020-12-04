<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersMusicInfoColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('my_music_title')->nullable();
            $table->string('my_music_artist')->nullable();
            $table->string('my_music_artwork')->nullable();
            $table->string('my_music_url')->nullable();
            $table->string('my_music_itunes_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('my_music_title');
            $table->dropColumn('my_music_artist');
            $table->dropColumn('my_music_artwork');
            $table->dropColumn('my_music_url');
            $table->dropColumn('my_music_itunes_url');
        });
    }
}
