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
                            <span class="headline">Edit Navigation Component</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col>
                                        <v-text-field v-model="text" label="Text"></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-text-field v-model="route" label="Route"></v-text-field>
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
                <v-btn 
                    v-if="this.text !== '' && this.route !== ''"
                    v-bind:href="isAdmin ? '../' + this.route : this.route" 
                    v-bind:target="isAdmin ? '_blank' : '_self'"
                    @click.prevent="loadRoute">
                        {{ this.text }}
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data: function() 
        {
            return {
                showEdit: true,
                dialog: false,
                text: "",
                route: "",
                selected: "",
            }
        },
        props: [
            "component",
            "isAdmin",
            "id"
        ],
        mounted() 
        {
            this.text = this.component.text;
            this.route = this.component.route;
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
            sendUpdateEvent: function()
            {
                //Sender et update event til factory component
                if(this.dialog === false)
                {
                    let event = {
                        componentType: "navigation",
                        route: this.route,
                        text: this.text,
                        id: this.id,
                    };

                    this.$emit("update:component", event);
                }
            },
            loadRoute: function()
            {
                let route = "";

                if(this.isAdmin)
                {
                    route =  '../' + this.route;
                }
                else
                {
                    route = this.route;
                }

                //Sender et event der loader en anden rute
                this.$emit('update:route', {
                    route: route,
                })
            }
        },
    }
</script>
<style scoped>
</style>
