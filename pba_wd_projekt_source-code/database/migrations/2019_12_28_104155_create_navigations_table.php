<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("text");
            $table->string("route");
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
        Schema::dropIfExists('navigation_components');
    }
}
