<?php

use App\Models\TextType;
use Illuminate\Database\Seeder;

class TextTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 5; $i++) { 
            $textType = new TextType([
                "name" => "h" . $i
            ]);
            $textType->save();
        }

        $textType = new TextType([
            "name" => "p"
        ]);
        $textType->save();

        $textType = new TextType([
            "name" => "i"
        ]);
        $textType->save();

        $textType = new TextType([
            "name" => "b"
        ]);
        $textType->save();

        $textType = new TextType([
            "name" => "u"
        ]);
        $textType->save();
    }
}
