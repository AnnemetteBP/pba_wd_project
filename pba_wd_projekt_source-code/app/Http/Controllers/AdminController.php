<?php

namespace App\Http\Controllers;

use App\Models\SiteSettings;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Alle ruter i back end vue applikationen bliver kørt her
     * 
     * @param $route den rute der skal vises
     * @return View som kører back end vue applikationen
     */
    public function spaAdmin()
    {
        //Side indstillingerne hentes fra databasen
        $siteSettings = SiteSettings::where("id", 1)->get()->first();
        //vue back end applikationen bliver startet i dette view og side indstillingerne bliver sendt med
        return view("admin", ["siteSettings" => $siteSettings]);
    }

    public function dashboard()
    {
        //Side indstillingerne hentes fra databasen
        $siteSettings = SiteSettings::where("id", 1)->get()->first();
        //vue admin dashboard applikationen bliver startet i dette view og side indstillingerne bliver sendt med
        return view("dashboard", ["siteSettings" => $siteSettings]);
    }
}
