<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\SiteSettings;
use Illuminate\Http\Request;

class RoutesController extends Controller
{    
    /**
     * Alle ruter i front end vue applikationen bliver kørt her
     * 
     * @param $route den rute der skal vises
     * @return View som kører front end vue applikationen
     */
    public function route($route = null)
    {
        //Side indstillingerne hentes fra databasen
        $siteSettings = SiteSettings::where("id", 1)->get()->first();
        //vue front end applikationen bliver startet i dette view og side indstillingerne bliver sendt med
        return view("spa", ["siteSettings" => $siteSettings]);
    }
}
