<?php

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GalleryImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $galleryImage = new GalleryImage([
            "gallery" => 1,
            "image" => 1
        ]);
        $galleryImage->save();
        
        $galleryImage = new GalleryImage([
            "gallery" => 1,
            "image" => 2
        ]);
        $galleryImage->save();
        
        $galleryImage = new GalleryImage([
            "gallery" => 1,
            "image" => 3
        ]);
        $galleryImage->save();
        
        $galleryImage = new GalleryImage([
            "gallery" => 1,
            "image" => 4
        ]);
        $galleryImage->save();
    }
}
