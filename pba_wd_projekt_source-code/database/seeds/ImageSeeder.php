<?php

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = new Image([
            "path" => "placeholder_light.png",
            "alt" => "A placeholder image in light colors.",
            "description" => "The placeholder image with some light color.",
        ]);
        $image->save();
        
        $image = new Image([
            "path" => "placeholder_small_light.png",
            "alt" => "A small placeholder image in light colors.",
            "description" => "The small placeholder image with some light color.",
        ]);
        $image->save();
        
        $image = new Image([
            "path" => "placeholder_square_light.jpg",
            "alt" => "A square placeholder image in light colors.",
            "description" => "The square placeholder image with some light color.",
        ]);
        $image->save();
        
        $image = new Image([
            "path" => "placeholder_wide_dark.png",
            "alt" => "A wide placeholder image in dark colors.",
            "description" => "The wide placeholder image with some dark color.",
        ]);
        $image->save();
        
        $image = new Image([
            "path" => "placeholder_wide_light.png",
            "alt" => "A wide placeholder image in light colors.",
            "description" => "The wide placeholder image with some light color.",
        ]);
        $image->save();
    }
}
