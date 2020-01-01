<?php

use App\Models\Component;
use App\Models\ImageComponent;
use Illuminate\Database\Seeder;

class ImageComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $component = new Component([
            "layout" => 1,
            "layout_area" => 1
        ]);
        $component->save();
        $imageComponent = new ImageComponent([
            "image" => 1,
            "component" => $component->id,
        ]);
        $imageComponent->save();

        $component = new Component([
            "layout" => 2,
            "layout_area" => 2
        ]);
        $component->save();
        $imageComponent = new ImageComponent([
            "image" => 2,
            "component" => $component->id,
        ]);
        $imageComponent->save();

        $component = new Component([
            "layout" => 2,
            "layout_area" => 3
        ]);
        $component->save();
        $imageComponent = new ImageComponent([
            "image" => 3,
            "component" => $component->id,
        ]);
        $imageComponent->save();

        $component = new Component([
            "layout" => 1,
            "layout_area" => 1
        ]);
        $component->save();
        $imageComponent = new ImageComponent([
            "image" => 4,
            "component" => $component->id,
        ]);
        $imageComponent->save();
    }
}
