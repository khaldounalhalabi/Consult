<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expert_id');
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade') ;
            $table->unsignedBigInteger('user_id') ;
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade') ;
            $table->text('comment')->nullable();
            $table->integer('star_rating')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_reviews');
    }
};
