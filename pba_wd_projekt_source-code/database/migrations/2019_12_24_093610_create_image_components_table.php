<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("image");
            $table->foreign("image")->references("id")->on("images");
            $table->unsignedBigInteger("component");
            $table->foreign("component")->references("id")->on("components");
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
        Schema::dropIfExists('image_components');
    }
}
