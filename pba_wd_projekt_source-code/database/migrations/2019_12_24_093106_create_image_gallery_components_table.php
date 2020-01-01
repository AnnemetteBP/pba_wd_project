<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageGalleryComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_gallery_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("pictures_per_row")->default(1);
            $table->boolean("has_border")->default(false);
            $table->string("gallery_title");
            $table->integer("image_widths")->nullable();
            $table->integer("image_heights")->nullable();
            $table->integer("margin")->default(0);
            $table->integer("padding")->default(0);
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
        Schema::dropIfExists('image_gallery_components');
    }
}
