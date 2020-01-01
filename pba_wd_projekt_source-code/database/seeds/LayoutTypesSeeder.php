<?php

use App\Models\LayoutType;
use Illuminate\Database\Seeder;

class LayoutTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layoutType = new LayoutType([
            "name" => "SingleArea"
        ]);
        $layoutType->save();

        
        $layoutType = new LayoutType([
            "name" => "LeftSplitted"
        ]);
        $layoutType->save();
    }
}
