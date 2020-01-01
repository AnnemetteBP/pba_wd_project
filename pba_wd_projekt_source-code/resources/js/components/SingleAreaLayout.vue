<template>
    <v-row :style="isAdmin ? 'background-color:#F5F5F5;border:1px solid #E0E0E0;' : ''">
        <v-col cols="12">
            <factory-component 
            v-on:update:component="emitComponentEvent($event)" 
            v-on:update:route="emitEvent($event)" 
            v-bind:isAdmin="isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:key="key" 
            v-bind:id="component.id"
            v-for="(component, key) in this.mainComponents" 
            v-bind:component="component" 
            v-bind:componentType="component.componentType"></factory-component>
        </v-col>
        <v-col cols="12" class="d-flex justify-center">
            <v-btn 
                v-show="isAdmin" 
                x-small 
                dark
                fab
                color="green darken-1"
                v-on:click="addComponent">
                    <v-icon x-small>fa fa-plus</v-icon>
            </v-btn>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        data: function() {
            return {
                mainComponents: [],
            }
        },
        props: [
            'layoutAreas',
            'isAdmin',
            'adminImages',
        ],
        mounted() {
            if(this.layoutAreas[0] !== null)
            {
                if(this.layoutAreas[0].name === "main")
                {
                    //Indlæser komponenterne i layout området
                    this.mainComponents = this.layoutAreas[0].components;
                }
            }
        },
        methods: {
            emitComponentEvent: function(event)
            {
                //Updater layout områderne med det opdaterede komponent
                for(let i = 0; i < this.mainComponents.length; i++)
                {
                    if(this.mainComponents[i].id === event.id && this.mainComponents[i].componentType === event.componentType)
                    {
                        this.mainComponents[i] = event;
                        break;
                    }
                    else if(this.mainComponents[i].componentType === "")
                    {
                        this.mainComponents[i] = event;
                        break;
                    }
                }

                let layoutEvent = {
                    layoutType: "SingleAreaLayout",
                    layoutAreas: [
                        {
                            name: "main",
                            components: this.mainComponents,
                        }
                    ],
                };
                //Send update eventet videre til admin applikationen
                this.$emit("update:component", layoutEvent);
            },
            emitEvent: function(event)
            {
                //Send update event med en ny rute der skal vises
                this.$emit('update:route', event);
            },
            addComponent: function()
            {
                //Opretter et nyt komponent uden type
                let component = {
                    componentType: ""
                };

                this.mainComponents.push(component);
            }
        }
    }
</script>
