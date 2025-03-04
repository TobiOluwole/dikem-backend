<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique('slug');
            $table->json('title');
            $table->json('content');
            $table->boolean('visible');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }    

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
} 