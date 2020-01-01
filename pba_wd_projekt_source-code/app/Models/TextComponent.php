<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TextComponent extends Model
{
    //Henter automatisk tekst typen og relationen til et layout område
    protected $with = [
        "textType",
        "component"
    ];

    //Henter tekst typen for teksten
    public function textType()
    {
        return $this->hasOne(TextType::class, "id", "text_type");
    }
    //Henter relationen imellem komponentet og layout området
    public function component()
    {
        return $this->hasOne(Component::class, "id", "component");
    }
}
