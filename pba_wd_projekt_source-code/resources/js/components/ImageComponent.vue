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
                                        <v-select v-on:change="setPath" v-model="selected" :items="adminImages" label="Choose image"></v-select>
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
                <img v-bind:src="this.path" v-bind:alt="this.alt" v-bind:description="this.description">
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
                path: "",
                alt: "",
                description: "",
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
            this.path = this.component.path;
            this.alt = this.component.alt;
            this.description = this.component.description;

            if(this.isAdmin){
                this.path = "../" + this.path;
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
                        componentType: "image",
                        path: this.path,
                        alt: this.alt,
                        description: this.description,
                        id: this.id,
                    };

                    this.$emit("update:component", event);
                }
            },
            setPath: function(){
                if(this.selected !== 'undefined'){
                    //Hvis det er admin applikationen
                    if(this.isAdmin){
                        //Tilføj .// foran billed stien
                        this.path = "../" + this.selected;
                    }else{
                        this.path = this.selected;
                    }
                }
            }
        },
    }
</script>
<style scoped>
</style>
