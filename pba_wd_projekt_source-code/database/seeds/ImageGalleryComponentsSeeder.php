<?php

use App\Models\Component;
use App\Models\ImageGalleryComponent;
use Illuminate\Database\Seeder;

class ImageGalleryComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $component = new Component([
            "layout" => 2,
            "layout_area" => 3
        ]);
        $component->save();
        $gallery = new ImageGalleryComponent([
            "pictures_per_row" => 3,
            "has_border" => true,
            "gallery_title" => "The gallery",
            "component" => $component->id,
            "margin" => 2,
            "padding" => 4,
        ]);
        $gallery->save();
    }
}
