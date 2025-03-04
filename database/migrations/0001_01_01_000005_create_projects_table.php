<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique('slug');
            $table->json('title');
            $table->json('content');
            $table->boolean('completed');
            $table->foreignId('user_id')->constrained('users');
            $table->json('images');
            $table->timestamps();
        });
    }    

    public function down()
    {
        Schema::dropIfExists('projects');
    }
} 