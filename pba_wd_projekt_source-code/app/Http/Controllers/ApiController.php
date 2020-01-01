<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\GalleryImage;
use App\Models\Image;
use App\Models\ImageComponent;
use App\Models\ImageGalleryComponent;
use App\Models\Layout;
use App\Models\LayoutArea;
use App\Models\LayoutType;
use App\Models\NavigationComponent;
use App\Models\SiteSettings;
use App\Models\TextComponent;
use App\Models\TextType;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Gemmer side indstillingerne
     * 
     * @param $request Post request med side indstillingerne fra dashboard vue applikationen
     */
    public function apiSettingsRoute(Request $request)
    {
        //Finder side indstillingerne fra databasen
        $siteSettings = SiteSettings::where("id", 1)->get()->first();
        //Sætter sidens title
        $siteSettings->title = $request->input("siteTitle");
        //Sætter sidens ejer
        $siteSettings->owner = $request->input("siteOwner");
        //Sætter sidens log billede
        $siteSettings->logo = $request->input("siteLogo");
        //Gemmer indstillingerne i databasen
        $siteSettings->save();
    }   

    /**
     * Sender alle billeder der er uploaded som json
     * Laravel konverterer selv fra PHP array til json
     * 
     * @return array der indeholder alle billederne der er uploaded
     */
    public function apiImageRoute()
    {
        //Finder alle billederne der er gemt i databasen som et array
        return Image::all()->toArray();
    }

    /**
     * Gemmer et billede der bliver uploaded og gemmer informationen i databasen
     * Billedet bliver flyttet til storage/app/public/images
     * Billedets filnavn bliver gemt i databasen
     * 
     * @param $request Post request med det billede der er uploaded fra dashboard vue applikationen
     */
    public function apiImageUploadRoute(Request $request)
    {
        //Lavet et billed filnavn med med tiden i sekunder i starten
        $fileName = time() . '_'. $request->file->getClientOriginalName();
        //Billed filen bliver flyttet til storage/app/public/images og kan findes i public/images
        $request->file->move(public_path('storage/images'), $fileName);
        //Et billede bliver oprettet til databasen
        $image = new Image();
        //Billedets filnavn bliver tilføjet
        $image->path = $fileName;
        //Billedet bliver gemt i databasen
        $image->save();
    }

    /**
     * Sender json der indeholder det layout der er gemt på ruten i applikationen
     * 
     * @param $route ruten i applikationen
     * @return array der indeholder layoutet for en rute i applikationen
     */
    public function apiRoute($route = null)
    {
        //Hvis ruten ikke er sat, sættes den til home
        if($route == null)
        {
            $route = "home";
        }
        //Tilføjer '/' i starten af ruten
        $route = "/" . $route;
        //Finder det layout der har ruten
        $layout = Layout::where("route", $route)->get()->first();
        //Layoutet og alle komponenterne bliver læst ind i et array
        $json = $layout->read();        
        //Arrays bliver konverteret til json automatisk af laravel
        return $json;
    }

    /**
     * Gemmer det json der er oprettet eller ændret for en rute i admin applikationen.
     * Først slettet alle de komponenter og det layout der måtte være for en rute.
     * Bagefter gemmes alt det nye.
     * Sender json der indeholder det layout der er gemt på ruten i applikationen
     * 
     * @param $request Post requestet fra admin applikationen
     * @return array der indeholder layoutet for en rute i applikationen
     */
    public function apiAdminRoute(Request $request)
    {
        //Ruten der vil oprettes eller ændres for
        $route = $request->input("route");
        //Layout navnet
        $layoutName = $request->input("layoutName");
        //Layout typen (SingleArea eller LeftSplitted)
        $layoutType = $request->input("layoutType");
        //Layout områderne hvor komponenter kan indsættes
        $layoutAreas = $request->input("layoutAreas");
        //Finder ud af om der allerede er gemt et layout for denne rute
        $layout = Layout::where("route", "/" . $route)->get()->first();
        //Hvis der er et layout allerede skal de gamle komponenter slettes fra databasen
        if($layout !== null)
        {
            //Den gamle layout type for ruten findes i databasen
            $oldLayoutType = LayoutType::where("id", $layout->layout_type)->get()->first();
            //Den nye layout type der skal gemmes findes i databasen
            $layoutType = LayoutType::where("name", $layoutType)->get()->first();
            //Alle layout områderne findes i databasen for dette layout
            $areas = LayoutArea::where("layout_type", $oldLayoutType->id)->get();
            //For hvert layout område skal alle de gamle komponenterne slettes
            foreach ($areas as $key => $layoutArea) 
            {
                //For hvert layout område findes alle komponenterne i layout området
                foreach ($layout->components($layoutArea->id) as $key => $component)
                {
                    //Finder ud af hvilken type komponentet er
                    if($component["componentType"] === "text")
                    {
                        //Hvis det er tekst slettes teksten i databasen
                        $textComponent = TextComponent::where("id", $component["id"])->get()->first()->delete();
                    }
                    else if($component["componentType"] === "image")
                    {
                        //Hvis det er et billede slettes billede komponentet i databasen
                        $imageComponent = ImageComponent::where("id", $component["id"])->get()->first()->delete();
                    }
                    else if($component["componentType"] === "imageGallery")
                    {
                        //Hvis det er et billede galleri slettes billede galleriet komponentet i databasen
                        $imageGalleryComponent = ImageGalleryComponent::where("id", $component["id"])->get()->first();
                        //Alle relationenerne imellem billede galleriet og galleri findes i databasen
                        $galleryImages = GalleryImage::where("gallery", $imageGalleryComponent->id)->get();
                        //For hvert galleri billede
                        foreach($galleryImages as $key => $galleryImage)
                        {
                            //Slettes relationen imellem billed galleriet og gfalleri billederne
                            GalleryImage::where("id", $galleryImage->id)->get()->first()->delete();
                        }
                        //Billed galleriet slettes fra databasen
                        $imageGalleryComponent->delete();
                    }
                    else if($component["componentType"] === "navigation")
                    {
                        //Hvis det er en navigation slettes navigations komponentet i databasen
                        $navigationComponent = NavigationComponent::where("id", $component["id"])->get()->first()->delete();
                    }
                }
            }
            //Alle relationerne imellem komponenterne og layout områderne findes i databasen
            $components = Component::where("layout", $layout->id)->get();
            //For hver komponent relation til et layout område
            foreach($components as $key => $component)
            {
                //Slettes relationen imellem et komponent og layout området
                Component::where("id", $component["id"])->get()->first()->delete();
            }
            //Det gamle layout bliver slettet i databasen
            $layout->delete();
            //Et nyt layout bliver oprettet
            $layout = new Layout();
            //Layout navnet bliver gemt
            $layout->name = $layoutName;
            //Layout ruten bliver gemt
            $layout->route = "/" . $route;
            //Layout typen bliver gemt
            $layout->layout_type = intval($layoutType->id);
            //Layoutet bliver gemt
            $layout->save();
        }
        else
        {     
            //Layout typen bliver fundet i databasen       
            $layoutType = LayoutType::where("name", $layoutType)->get()->first();
            //Et nyt layout bliver oprettet
            $layout = new Layout();
            //Layout navnet bliver gemt
            $layout->name = $layoutName;
            //Layout ruten bliver gemt
            $layout->route = "/" . $route;
            //Layout typen bliver gemt
            $layout->layout_type = intval($layoutType->id);
            //Layoutet bliver gemt
            $layout->save();
        }
        //For hvert af de nye layout områder
        foreach ($layoutAreas as $key => $area) 
        {
            //Layout området bliver fundet i databasen
            $layoutArea = LayoutArea::where("layout_type", $layout->layout_type)->where("name", $area["name"])->get()->first();
            //For hvert komponent i layout området
            foreach ($area["components"] as $key => $component) 
            {
                //En ny relation oprettes imellem layout området og det nye komponent
                $comp = new Component();
                //Layoutet bliver gemt i relationen
                $comp->layout = $layout->id;
                //Layoutet området bliver gemt i relationen
                $comp->layout_area = $layoutArea->id;
                //Relationen bliver gemt i databasen
                $comp->save();
                //Der findes ud af om det nye komponent er tekst
                if($component["componentType"] === "text")
                {          
                    if(isset($component["text"]) === true)
                    {
                        //Text typen findes for komponentet
                        $textType = TextType::where("name", $component["textType"])->get()->first();
                        //Et nyt tekst komponent oprettes
                        $textComponent = new TextComponent();
                        //Teksten bliver gemt
                        $textComponent->text = $component["text"];
                        //Tekst typen bliver gemt
                        $textComponent->text_type = $textType->id;
                        //Relationen til layout området bliver gemt
                        $textComponent->component = $comp->id;
                        //Komponentet bliver gemt i databasen
                        $textComponent->save();
                    }  
                }
                //Der findes ud af om det nye komponent en navigation
                if($component["componentType"] === "navigation")
                {
                    if(isset($component["route"]) === true)
                    {
                        //Et nyt navigations komponent oprettes
                        $navigationComponent = new NavigationComponent();
                        //Teksten bliver gemt
                        $navigationComponent->text = $component["text"];
                        //Ruten bliver gemt
                        $navigationComponent->route = $component["route"];
                        //Relationen til layout området bliver gemt
                        $navigationComponent->component = $comp->id;
                        //Komponentet bliver gemt i databasen
                        $navigationComponent->save();
                    }  
                }
                //Der findes ud af om det nye komponent et billede
                if($component["componentType"] === "image")
                {
                    if(isset($component["path"]) === true)
                    {
                        //Stien til billedet i databasen
                        $path = "";
                        if(substr($component["path"], 0, 3) === "../")
                        {
                            //Hvis billedets sti starter med ../ fjernes det foran billed filnavnet indtil kun billed filnavnet er tilbage
                            $path = substr($component["path"], 18);
                        }
                        else
                        {
                            //Fjerner alt det foran billed filnavnet indtil kun billed filnavnet er tilbage
                            $path = substr($component["path"], 15);
                        }
                        //Billedet i databasen bliver fundet
                        $image = Image::where("path", $path)->get()->first();
                        //Et nyt billed komponent oprettes
                        $imageComponent = new ImageComponent();
                        //Relationen til billedet i databasen bliver gemt
                        $imageComponent->image = $image->id;
                        //Relationen til layout området bliver gemt
                        $imageComponent->component = $comp->id;
                        //Komponentet bliver gemt i databasen
                        $imageComponent->save();
                    } 
                }
                //Der findes ud af om det nye komponent et billed galleri
                if($component["componentType"] === "imageGallery")
                {          
                    if(isset($component["galleryTitle"]) === true)
                    {
                        //Billed galleriet findes i databasen
                        $imageGalleryComponent = new ImageGalleryComponent();
                        //Finder ud af om værdien for billeder per række er ok
                        if(intval($component["picturesPerRow"]) < 1)
                        {
                            //Hvis den ikke er ok sættes den til 1
                            $imageGalleryComponent->pictures_per_row = 1;
                        }
                        else
                        {
                            //Hvis den er ok så gemmes værdien
                            $imageGalleryComponent->pictures_per_row = intval($component["picturesPerRow"]);
                        }    
                        //Finder ud af om værdien for om billderne skal have en border omkring er ok
                        if($component["hasBorder"] !== true)
                        {
                            //Hvis den ikke er ok sættes den til nej
                            $imageGalleryComponent->has_border = false;
                        }
                        else
                        {
                            //Hvis den er ok så gemmes værdien
                            $imageGalleryComponent->has_border = true;
                        }
                        //Galleri titlen gemmes
                        $imageGalleryComponent->gallery_title = $component["galleryTitle"];    
                        //Finder ud af om værdien for margin er ok
                        if(empty($component["margin"]))
                        {
                            //Hvis den ikke er ok sættes den til 0
                            $imageGalleryComponent->margin = 0;
                        }
                        else
                        {
                            //Hvis den er ok så gemmes værdien
                            $imageGalleryComponent->margin = intval($component["margin"]);
                        }                        
                        //Finder ud af om værdien for padding er ok
                        if(empty($component["padding"]))
                        {
                            //Hvis den ikke er ok sættes den til 0
                            $imageGalleryComponent->padding = 0;
                        }
                        else
                        {
                            //Hvis den er ok så gemmes værdien
                            $imageGalleryComponent->padding = intval($component["padding"]);
                        }
                        //Finder ud af om værdien for billed bredden er ok
                        if(empty($component["imageWidths"]))
                        {
                            //Hvis den ikke er ok sættes den til 0
                            $imageGalleryComponent->image_widths = 0;
                        }
                        else
                        {
                            //Hvis den er ok så gemmes værdien
                            $imageGalleryComponent->image_widths = intval($component["imageWidths"]);
                        }
                        //Finder ud af om værdien for billed højden er ok
                        if(empty($component["iamgeHeights"]))
                        {
                            //Hvis den ikke er ok sættes den til 0
                            $imageGalleryComponent->image_heights = 0;
                        }
                        else
                        {
                            //Hvis den er ok så gemmes værdien
                            $imageGalleryComponent->image_heights = intval($component["iamgeHeights"]);
                        }
                        //Relationen til layout området gemms
                        $imageGalleryComponent->component = $comp->id;
                        //Billed galleriet gemmes i databasen
                        $imageGalleryComponent->save();
                        //For hvert billede oprettes en relation imellem billed galleriet og billedet i databasen
                        foreach($component["images"] as $key => $image)
                        {
                            //Billed filnavnet
                            $pictureName = "";
                            //Den sti der er på billedet
                            $path = $image["path"];
                            //Finder ud af om stien på billedet start med ../
                            if(substr($path, 0, 3) === "../")
                            {
                                //Hvis det gør fjernes det samt alt indtil kun billed filnavnet er tilbage
                                $pictureName = substr($path, 18);
                            }
                            else
                            {
                                //Ellers fjernes alt indtil kun billed filnavnet er tilbage
                                $pictureName = substr($path, 15);
                            }
                            //Billedet findes i databasen
                            $image = Image::where("path", $pictureName)->get()->first();
                            //En ny relation oprettes imellem billed galleriet og og billedet
                            $galleryImage = new GalleryImage();
                            //Billedet gemmes i relationen
                            $galleryImage->image = $image->id;
                            //Billed galleriet gemmes i relationen
                            $galleryImage->gallery = $imageGalleryComponent->id;
                            //Relationen imellem billedet og billed galleriet gemmes i databasen
                            $galleryImage->save();
                        }
                    } 
                }
            }
        }
        //Det nye layout bliver sendt som json
        return $layout->read();
    }
}
