<?php

use App\Models\Component;
use App\Models\NavigationComponent;
use Illuminate\Database\Seeder;

class NavigationsSeeder extends Seeder
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
            "layout_area" => 2
        ]);
        $component->save();
        
        $navigationComponent = new NavigationComponent([
            "text" => "Velkommen",
            "route" => "welcome",
            "component" => $component->id
        ]);
        $navigationComponent->save();

        $component = new Component([
            "layout" => 1,
            "layout_area" => 1
        ]);
        $component->save();
        
        $navigationComponent = new NavigationComponent([
            "text" => "Forsiden",
            "route" => "home",
            "component" => $component->id
        ]);
        $navigationComponent->save();
    }
}
