<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(LayoutTypesSeeder::class);
        $this->call(LayoutAreasSeeder::class);
        $this->call(TextTypesSeeder::class);
        $this->call(LayoutsSeeder::class);
        $this->call(TextComponentsSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(ImageGalleryComponentsSeeder::class);
        $this->call(ImageComponentsSeeder::class);
        $this->call(GalleryImagesSeeder::class);
        $this->call(NavigationsSeeder::class);
        $this->call(SiteSettingsSeeder::class);
    }
}
