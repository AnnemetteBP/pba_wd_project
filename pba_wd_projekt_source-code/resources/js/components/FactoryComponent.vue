<template>
    <v-container v-if="isDeleted === false">
        <text-component 
            v-bind:id="this.id" 
            v-on:update:component="emitComponentEvent($event)" 
            v-on:update:deleteComponent="emiteDeleteComponent($event)" 
            v-if="this.componentTypeData === 'text'" 
            v-bind:isAdmin="isAdmin" 
            v-bind:component="this.componentData"></text-component>
        <image-component 
            v-bind:id="this.id" 
            v-on:update:component="emitComponentEvent($event)" 
            v-on:update:deleteComponent="emiteDeleteComponent($event)" 
            v-else-if="this.componentTypeData === 'image'" 
            v-bind:isAdmin="isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:component="this.componentData"></image-component>
        <image-gallery-component 
            v-bind:id="this.id" 
            v-on:update:component="emitComponentEvent($event)" 
            v-on:update:deleteComponent="emiteDeleteComponent($event)" 
            v-else-if="this.componentTypeData === 'imageGallery'" 
            v-bind:isAdmin="isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:component="this.componentData"></image-gallery-component>
        <navigation-component 
            v-bind:id="this.id" 
            v-on:update:component="emitComponentEvent($event)" 
            v-on:update:deleteComponent="emiteDeleteComponent($event)" 
            v-on:update:route="emitEventToLayout($event)" 
            v-else-if="this.componentTypeData === 'navigation'" 
            v-bind:isAdmin="isAdmin" 
            v-bind:component="this.componentData"></navigation-component>
        <v-select 
            v-else-if="isAdmin && this.componentTypeData === ''" 
            v-on:change="setComponent" 
            v-model="selected" 
            :items="componentTypes" 
            label="Choose component type"></v-select>
    </v-container>
</template>

<script>
    export default {
        data: function()
        {
            return {
                isDeleted: false,
                selected: "",
                componentTypeData: "",
                componentData: "",
                componentTypes: [
                    "Text Component",
                    "Image Component",
                    "Image Gallery Component",
                    "Navigation Component"
                ],
            }
        },
        props: [
            'componentType',
            'component',
            'isAdmin',
            'adminImages',
            'id',
        ],
        mounted() 
        {
            this.componentTypeData = this.componentType;
            this.componentData = this.component;
        },
        methods: {
            emiteDeleteComponent: function(event)
            {
                //Send event med at et komponent er slettet
                this.componentTypeData = "";
                this.selected = "";
                event = {
                    id: this.componentData.id,
                    componentType: this.componentData.componentType,
                };
                this.$emit("update:component", event);
                this.isDeleted = true;
            },
            emitComponentEvent: function(event)
            {
                //Updater kopmonentet der er blevet ændret
                if(event.componentType === "text")
                {
                    this.componentData = {
                        componentType: event.componentType,
                        textType: event.textType,
                        text: event.text,
                        id: event.id,
                    };
                }
                else if(event.componentType === "navigation")
                {
                    this.componentData = {
                        componentType: event.componentType,
                        route: event.route,
                        text: event.text,
                        id: event.id,
                    };
                }
                else if(event.componentType === "image")
                {
                    this.componentData = {
                        componentType: event.componentType,
                        path: event.path,
                        alt: event.alt,
                        description: event.description,
                        id: event.id,
                    };
                }
                else if(event.componentType === "imageGallery")
                {
                    this.componentData = {
                        componentType: event.componentType,
                        galleryTitle: event.galleryTitle,
                        picturesPerRow: event.picturesPerRow,
                        hasBorder: event.hasBorder,
                        imageWidths: event.imageWidths,
                        imageHeights: event.imageHeights,
                        margin: event.margin,
                        padding: event.padding,
                        images: event.images,
                        id: event.id,
                    };
                }

                //Send update event med et komponent der er ændret
                this.$emit("update:component", event);
            },
            emitEventToLayout: function(event)
            {
                //Send event med at en ny rute skal vises
                this.$emit('update:route', event);
            },
            setComponent: function()
            {
                //Sætter komponent typen når en type er valgt i admin applikationen
                if(this.selected === "Text Component")
                {
                    this.componentTypeData = "text";
                    this.componentData = {
                        componentType: "text",
                        text: "",
                        textType: ""
                    };
                }
                else if(this.selected === "Image Component")
                {
                    this.componentTypeData = "image";
                    this.componentData = {
                        componentType: "image",
                        path: "",
                        alt: "",
                        description: "",
                    };
                }
                else if(this.selected === "Image Gallery Component")
                {
                    this.componentTypeData = "imageGallery";
                    this.componentData = {
                        componentType: "imageGallery",
                        images: [],
                        imageHeights: "",
                        imageWidths: "",
                        galleryTitle: "",
                        hasBorder: "",
                        picturesPerRow: "",
                    };
                }
                else if(this.selected === "Navigation Component")
                {
                    this.componentTypeData = "navigation";
                    this.componentData = {
                        componentType: "navigation",
                        text: "",
                        route: ""
                    };
                }
            }
        }
    }
</script>
