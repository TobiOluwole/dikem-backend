<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique('slug');
            $table->json('title');
            $table->json('content');
            $table->timestamp('datetime');
            $table->string('type')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->json('images')->default('[]');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
