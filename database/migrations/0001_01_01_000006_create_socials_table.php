<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSocialsTable extends Migration
{
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('phone')->nullable();
            $table->string('x')->nullable();
            $table->string('instagram')->nullable();
            $table->string('email')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        DB::table('socials')->insert([
            ['id'=>1]
        ]);
    }    

    public function down()
    {
        Schema::dropIfExists('socials');
    }
} 