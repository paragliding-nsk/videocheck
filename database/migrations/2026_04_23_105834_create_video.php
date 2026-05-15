<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
                  
            $table->string('ytbID');
            $table->string('title')->nullable();
            $table->mediumText('descript')->nullable();
            $table->string('uploadDate')->nullable();
            $table->integer('duration')->nullable();
            $table->string('isConf')->nullable();
            $table->mediumText('contentShort')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('channelID')->nullable();
            $table->string('lang')->nullable();
            $table->mediumText('channelName')->nullable();
            $table->mediumText('contentTimeCodes')->nullable();
            $table->mediumText('reserv2')->nullable();
            $table->mediumText('reserv3')->nullable();
            $table->mediumText('reserv4')->nullable();

            $table->softDeletes();
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_video');
    }
};
