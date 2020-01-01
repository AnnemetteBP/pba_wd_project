<?php

use App\Models\LayoutArea;
use Illuminate\Database\Seeder;

class LayoutAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layoutArea = new LayoutArea([
            "name" => "main",
            "layout_type" => 1
        ]);
        $layoutArea->save();
        
        $layoutArea = new LayoutArea([
            "name" => "left",
            "layout_type" => 2
        ]);
        $layoutArea->save();
        
        $layoutArea = new LayoutArea([
            "name" => "main",
            "layout_type" => 2
        ]);
        $layoutArea->save();
    }
}
