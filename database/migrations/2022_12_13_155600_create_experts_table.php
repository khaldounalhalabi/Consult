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
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->string('name') ;
            $table->string('email')->unique() ;
            $table->string('password') ;
            $table->text('photo')->nullable() ;
            $table->text('experience') ;
            $table->string('phone') ;
            $table->string('mobile') ;
            $table->string('country') ;
            $table->string('city') ;
            $table->string('street') ;
            $table->float('price')->default(0) ;
            $table->float('wallet')->default(0) ;

            $table->unsignedBigInteger('category_id') ;
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade') ;

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
        Schema::dropIfExists('experts');
    }
};
