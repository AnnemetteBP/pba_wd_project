<template>
    <v-container>
        <v-row>
            <v-col cols="12" class="d-flex justify-end">
                <v-btn 
                    color="red darken-1" 
                    dark 
                    x-small              
                    v-on:click="emiteDeleteComponent" 
                    v-show="isAdmin && this.componentTypeData !== ''">
                        <v-icon x-small>fa fa-trash</v-icon>
                </v-btn>

                <v-dialog v-if="isAdmin === true" v-model="dialog" persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn 
                            color="blue darken-2" 
                            dark 
                            x-small 
                            v-on="on">
                                <v-icon x-small>fa fa-cogs</v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">Edit Image Component</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="galleryTitle" label="Gallery Title"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field v-model="picturesPerRow" label="Images per row"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-switch v-model="hasBorder" label="Image borders"></v-switch>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field v-model="imageWidths" label="Image widths"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field v-model="imageHeights" label="Image heights"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-select 
                                            v-on:change="setImages" 
                                            v-model="selected" 
                                            :items="adminImages" 
                                            label="Choose image" 
                                            multiple></v-select>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-3" dark block @click="dialog = false" v-on:click="sendUpdateEvent">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-col>
            <v-col cols="12">
                <h2>{{ galleryTitle }}</h2>
            </v-col>
            <v-col cols="12">
                <v-row>
                    <v-col v-bind:cols="parseInt((12 / picturesPerRow))" v-bind:key="image.id" v-for="image in this.images">
                        <img 
                        v-bind:src="image.path" 
                        v-bind:alt="image.alt" 
                        v-bind:description="image.description" 
                        v-bind:width="imageWidths !== null && imageWidths > 0 ? imageWidths : '100%'" 
                        v-bind:height="imageHeights !== null && imageHeights > 0 ? imageHeights : '100%'" 
                        v-bind:style="{ border: (hasBorder ? 1 : 0) + 'px solid black', margin: margin + 'px', padding: padding + 'px'}">
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data: function() {
            return {
                showEdit: true,
                dialog: false,
                picturesPerRow: "",
                hasBorder: "",
                galleryTitle: "",
                imageWidths: "",
                imageHeights: "",
                margin: "",
                padding: "",
                images: "",
                selected: "",
            }
        },
        props: [
            "component",
            "adminImages",
            "isAdmin",
            "id"
        ],
        mounted() {
            this.picturesPerRow = this.component.picturesPerRow;
            this.hasBorder = this.component.hasBorder;
            this.galleryTitle = this.component.galleryTitle;
            this.imageWidths = this.component.imageWidths;
            this.imageHeights = this.component.imageHeights;
            this.margin = this.component.margin;
            this.padding = this.component.padding;
            this.images = this.component.images;

            if(this.isAdmin)
            {
                this.images.forEach(i => {
                    i.path = "../" + i.path;
                });
            }
        },
        methods: {
            emiteDeleteComponent: function()
            {
                let event = {
                    componentType: "text",
                    textType: this.textType,
                    text: this.text,
                    id: this.id,
                };

                this.$emit("update:deleteComponent", event);
            },
            sendUpdateEvent: function(){
                //Sender et update event til factory component
                if(this.dialog === false)
                {
                    let event = {
                        componentType: "imageGallery",
                        picturesPerRow: this.picturesPerRow,
                        hasBorder: this.hasBorder,
                        galleryTitle: this.galleryTitle,
                        imageWidths: this.imageWidths,
                        imageHeights: this.imageHeights,
                        margin: this.margin,
                        padding: this.padding,
                        images: this.images,
                        id: this.id,
                    };

                    this.$emit("update:component", event);
                }
            },
            setImages: function(){
                this.images = [];

                //Hvis der er nogle billeder i galleriet
                if(this.selected !== "undefined"){
                    //For hver billede
                    this.selected.forEach(s => {
                        let path = s;
                        //Hvis det er admin applikationen
                        if(this.isAdmin)
                        {
                            //Tilføj ../ foran billed stien for at få ruten rigtig
                            path = "../" + path;
                        }
                        else
                        {
                            path = path;
                        }
                        //Tilføj billedet til listen med billeder
                        this.images.push({
                            path: path,
                            alt: "",
                            description: "",
                        });
                    });
                }
            }
        },
    }
</script>
<style scoped>
</style>
