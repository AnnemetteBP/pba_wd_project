<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    /**
     * Finder alle komponeter der er i layout områderne for layoutet
     * 
     * @return array med alle layout områderne og de komponenter der er i layout områderne
     */
    public function read()
    {
        //Findet layout typen
        $layoutType = $this->layoutType();
        //Finder layout områderne
        $layoutAreas = $layoutAreas = LayoutArea::where("layout_type", $layoutType->id)->get();
        //Et array til alle komponenterne i layout områderne
        $layoutAreasAndComponents = [];
        //For hvert layout område
        foreach ($layoutAreas as $key => $area) 
        {
            //Findes komponenterne for layout området i databasen
            $components = $this->components($area->id);
            //Komponenterne tilføjes layout området sammen med navnet for området
            $layoutAreasAndComponents[] = [
                "name" => $area->name,
                "components" => $components
            ];
        }
        //Finder alle layouts i databasen
        $layouts = Layout::all();
        //Et array til at have alle layout i
        $layoutsArray = [];
        //For hvert layout gemmes layout type, navnet og ruten i det array
        foreach ($layouts as $key => $layout) {
            $layoutsArray[] = [
                "layoutType" => $layout->layout_type,
                "name" => $layout->name,
                "route" => $layout->route,
            ];
        }
        //Der sendes et array med ruten, layout navnet, layout typens navn, layout områderne med komponenter, og de layout typer der er i databasen
        return [
            "name" => $this->name,
            "route" => $this->route,
            "layoutType" => $layoutType->name,
            "layoutAreas" => $layoutAreasAndComponents,
            "layouts" => $layoutsArray,
        ];
    }

    /**
     * Finder layout type for layoutet
     * 
     * @return LayoutType fra databasen
     */
    public function layoutType()
    {
        return $this->belongsTo(LayoutType::class, "layout_type", "id")->get()->first();
    }

    /**
     * Finder layout områderne for layoutet
     * 
     * @return Collection af layout områder fra databasen
     */
    public function layoutAreas()
    {
        return $this->hasManyThrough(LayoutArea::class, LayoutType::class, "id", "layout_type")->get();
    }

    /**
     * Finder komponenterne for et layout område i layoutet
     * 
     * @return array af komponenter
     */
    public function components($layoutAreaId)
    {
        //Finder alle komponeterne der har en relation til layout området
        $components = $this->hasMany(Component::class, "layout", "id")->get()->where("layout_area", $layoutAreaId);
        //Et array til alle komponenterne
        $allComponents = [];
        //For hver relation imellem området og et komponent
        foreach ($components as $key => $component) 
        {
            //Forsøger at finde et tekst komponent
            $textComponent = TextComponent::where("component", $component->id)->get()->first();
            //Hvis det blev fundet
            if($textComponent != null)
            {
                //Findes tekst typen
                $textType = $textComponent->textType->name;
                //Tekst typen og teksten gemmes til array
                $allComponents[] = [
                    "componentType" => "text",
                    "text" => $textComponent->text,
                    "textType" => $textType,
                    "id" => $textComponent->id,
                ];
                //Komponenten blev fundet så der skal ikke søges mere
                continue;
            }
            
            //Forsøger at finde et billed komponent
            $imageComponent = ImageComponent::where("component", $component->id)->get()->first();
            //Hvis det blev fundet
            if($imageComponent != null)
            {
                //Find billedet i databasen
                $image = Image::where("id", $imageComponent->image)->get()->first();
                //Billedet gemmes til array
                $allComponents[] = [
                    "componentType" => "image",
                    "path" => "storage/images/" . $image->path,
                    "alt" => $image->alt,
                    "description" => $image->description,
                    "id" => $imageComponent->id,
                ];
                //Komponenten blev fundet så der skal ikke søges mere
                continue;
            }
            
            //Forsøger at finde et billed galleri komponent
            $imageGalleryComponent = ImageGalleryComponent::where("component", $component->id)->get()->first();
            //Hvis det blev fundet
            if($imageGalleryComponent != null)
            {
                //Finder alle galleri billederne i databasen
                $galleryImages = GalleryImage::where("gallery", $imageGalleryComponent->id)->get();
                //Array til alle billederne
                $images = [];
                //For hvert billede i databasen tilføjes de til billed array
                foreach ($galleryImages as $key => $galleryImage) 
                {
                    $images[] = Image::where("id", $galleryImage->image)->get()->first();
                }
                //Foran hvert billed filnavn tilføjes stien til billedet /storage/images/
                foreach ($images as $key => $image) 
                {
                    $image->path = "storage/images/" . $image->path;
                }
                //Billed galleriet gemmes til array
                $allComponents[] = [
                    "componentType" => "imageGallery",
                    "picturesPerRow" => $imageGalleryComponent->pictures_per_row,
                    "hasBorder" => $imageGalleryComponent->has_border,
                    "galleryTitle" => $imageGalleryComponent->gallery_title,
                    "imageWidths" => $imageGalleryComponent->image_widths,
                    "imageHeights" => $imageGalleryComponent->image_heights,
                    "margin" => $imageGalleryComponent->margin,
                    "padding" => $imageGalleryComponent->padding,
                    "images" => $images,
                    "id" => $imageGalleryComponent->id,
                ];
                //Komponenten blev fundet så der skal ikke søges mere
                continue;
            }

            //Forsøger at finde et billed galleri komponent
            $navigationComponent = NavigationComponent::where("component", $component->id)->get()->first();
            //Hvis det blev fundet
            if($navigationComponent != null)
            {
                //Navigationen gemmes til array
                $allComponents[] = [
                    "componentType" => "navigation",
                    "text" => $navigationComponent->text,
                    "route" => $navigationComponent->route,
                    "id" => $navigationComponent->id,
                ];
                //Komponenten blev fundet så der skal ikke søges mere
                continue;
            }
        }
        //array med all komponenter i layoutet
        return $allComponents;
    }
}