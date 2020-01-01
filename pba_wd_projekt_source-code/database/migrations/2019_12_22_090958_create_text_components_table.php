<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("text");
            $table->unsignedBigInteger("text_type");
            $table->foreign("text_type")->references("id")->on("text_types");
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
        Schema::dropIfExists('text_components');
    }
}
