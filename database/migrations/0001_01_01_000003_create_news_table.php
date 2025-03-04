<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->json('title');
            $table->json('content');
            $table->boolean('visible');
            $table->json('tags')->nullable();
            $table->json('images')->nullable();
            $table->string('slug')->unique('slug');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
} 