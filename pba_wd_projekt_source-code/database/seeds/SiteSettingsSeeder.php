<?php

use App\Models\SiteSettings;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = new SiteSettings([
            "title" => "PB WD CMS",
            "owner" => "AM",
            "logo" => "placeholder_square_light.jpg",
        ]);
        $settings->save();
    }
}
