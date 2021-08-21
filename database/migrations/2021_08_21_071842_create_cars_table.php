<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('modal');
            $table->string('year');
            $table->string('price');
            $table->string('color')->nullable();
            $table->string('fuel')->nullable();
            $table->string('kilometer')->nullable();
            $table->string('mileage')->nullable();
            $table->string('no_of_owner')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
