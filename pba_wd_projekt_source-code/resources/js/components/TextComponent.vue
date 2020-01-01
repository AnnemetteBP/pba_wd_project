<template>
    <v-container>
        <v-row>
            <v-col cols="12" class="d-flex justify-end">
                <v-btn 
                    x-small
                    color="red darken-1" 
                    dark              
                    v-on:click="emiteDeleteComponent" 
                    v-show="isAdmin && this.componentTypeData !== ''">
                        <v-icon x-small>fa fa-trash</v-icon>
                    </v-btn>
                <v-dialog v-if="isAdmin === true" v-model="dialog" persistent max-width="600px">
                    <template v-slot:activator="{ on }">
                        <v-btn 
                            x-small 
                            color="blue darken-2" 
                            dark 
                            v-on="on">
                                <v-icon x-small>fa fa-cogs</v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">Edit Text Component</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col>
                                        <v-select v-on:change="setTextType" v-model="selected" :items="textTypes" label="Choose text type"></v-select>
                                    </v-col>
                                    <v-col>
                                        <v-text-field v-model="text" label="Text"></v-text-field>
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
                <p v-if="this.textType === 'p'">
                    {{ this.text }}
                </p>
                <h1 v-else-if="this.textType === 'h1'">
                    {{ this.text }}
                </h1>
                <h2 v-else-if="this.textType === 'h2'">
                    {{ this.text }}
                </h2>
                <h3 v-else-if="this.textType === 'h3'">
                    {{ this.text }}
                </h3>
                <h4 v-else-if="this.textType === 'h4'">
                    {{ this.text }}
                </h4>
                <b v-else-if="this.textType === 'b'">
                    {{ this.text }}
                </b>
                <i v-else-if="this.textType === 'i'">
                    {{ this.text }}
                </i>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data: function() {
            return {
                dialog: false,
                text: "",
                textType: "",
                textTypes: [
                    "paragraph",
                    "heading 1",
                    "heading 2",
                    "heading 3",
                    "heading 4",
                    "bold",
                    "italic"
                ],
                selected: "",
            }
        },
        props: [
            "component",
            "isAdmin",
            "id"
        ],
        mounted() {
            this.text = this.component.text;
            this.textType = this.component.textType;
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
            setTextType: function() {
                if(this.selected === "paragraph")
                {
                    this.textType = "p";
                }
                else if(this.selected === "heading 1")
                {
                    this.textType = "h1";
                }
                else if(this.selected === "heading 2")
                {
                    this.textType = "h2";
                }
                else if(this.selected === "heading 3")
                {
                    this.textType = "h3";
                }
                else if(this.selected === "heading 4")
                {
                    this.textType = "h4";
                }
                else if(this.selected === "bold")
                {
                    this.textType = "b";
                }
                else if(this.selected === "italic")
                {
                    this.textType = "i";
                }
            },
            sendUpdateEvent: function(){
                //Sender et update event til factory component
                if(this.dialog === false)
                {
                    let event = {
                        componentType: "text",
                        textType: this.textType,
                        text: this.text,
                        id: this.id,
                    };

                    this.$emit("update:component", event);
                }
            },
        },
    }
</script>
<style scoped>
</style>
