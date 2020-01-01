<?php

use App\Models\Component;
use App\Models\TextComponent;
use Illuminate\Database\Seeder;

class TextComponentsSeeder extends Seeder
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

        $textComponent = new TextComponent([
            "text" => "Where eagles dare to last caress you attitude, so scream with me.",
            "text_type" => 4,
            "component" => $component->id
        ]);
        $textComponent->save();

        $component = new Component([
            "layout" => 1,
            "layout_area" => 1,
        ]);
        $component->save();

        $textComponent = new TextComponent([
            "text" => "Endless nameless tourettes and territorial pissings.",
            "text_type" => 6,
            "component" => $component->id
        ]);
        $textComponent->save();

        $component = new Component([
            "layout" => 1,
            "layout_area" => 1,
        ]);
        $component->save();
        
        $textComponent = new TextComponent([
            "text" => "Come and smell like lithium teen spirit as you are.",
            "text_type" => 3,
            "component" => $component->id
        ]);
        $textComponent->save();

        $component = new Component([
            "layout" => 2,
            "layout_area" => 2,
        ]);
        $component->save();

        $textComponent = new TextComponent([
            "text" => "Endless nameless tourettes and territorial pissings.",
            "text_type" => 6,
            "component" => $component->id
        ]);
        $textComponent->save();

        $component = new Component([
            "layout" => 2,
            "layout_area" => 2,
        ]);
        $component->save();

        $textComponent = new TextComponent([
            "text" => "Where eagles dare to last caress you attitude, so scream with me.",
            "text_type" => 1,
            "component" => $component->id
        ]);
        $textComponent->save();

        $component = new Component([
            "layout" => 2,
            "layout_area" => 3,
        ]);
        $component->save();

        $textComponent = new TextComponent([
            "text" => "Come and smell like lithium teen spirit as you are.",
            "text_type" => 7,
            "component" => $component->id
        ]);
        $textComponent->save();
    }
}
