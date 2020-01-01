<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayoutAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->unsignedBigInteger("layout_type");
            $table->foreign("layout_type")->references("id")->on("layout_types");
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
        Schema::dropIfExists('layout_areas');
    }
}
