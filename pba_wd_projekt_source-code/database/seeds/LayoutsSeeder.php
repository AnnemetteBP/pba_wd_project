<?php

use App\Models\Layout;
use Illuminate\Database\Seeder;

class LayoutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layout = new Layout([
            "name" => "forsiden",
            "layout_type" => 1,
            "route" => "/welcome"
        ]);
        $layout->save();
        
        $layout = new Layout([
            "name" => "hjem",
            "layout_type" => 2,
            "route" => "/home"
        ]);
        $layout->save();
    }
}
