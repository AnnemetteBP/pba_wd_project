<template>
    <v-row>
        <v-col cols="4" :style="isAdmin ? 'background-color:#F5F5F5;border:1px solid #E0E0E0;' : ''">
            <v-col cols="12">
                <factory-component 
                v-on:update:component="emitComponentEvent($event, 'left')" 
                v-on:update:route="emitEvent($event)" 
                v-bind:isAdmin="isAdmin" 
                v-bind:adminImages="adminImages"  
                v-bind:key="key" 
                v-bind:id="component.id"
                v-for="(component, key) in this.leftComponents" 
                v-bind:component="component" 
                v-bind:componentType="component.componentType"></factory-component>
            </v-col>
            <v-col cols="12" class="d-flex justify-center">
                <v-btn 
                v-show="isAdmin" 
                x-small  
                fab
                dark
                color="green darken-1"
                v-on:click="addLeftComponent">
                    <v-icon x-small>fa fa-plus</v-icon>
                </v-btn>
            </v-col>
        </v-col>
        <v-col cols="8" :style="isAdmin ? 'background-color:#F5F5F5;border:1px solid #E0E0E0;' : ''">
            <v-col cols="12">
                <factory-component 
                    v-on:update:component="emitComponentEvent($event, 'main')" 
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
                fab 
                dark
                color="green darken-1"
                v-on:click="addMainComponent">
                    <v-icon x-small>fa fa-plus</v-icon>
                </v-btn>
            </v-col>
        </v-col>
    </v-row> 
</template>

<script>
    export default {
        data: function() {
            return {
                leftComponents: [],
                mainComponents: [],
            }
        },
        props: [
            'layoutAreas',
            'isAdmin',
            'adminImages',
        ],
        mounted() {
            this.layoutAreas.forEach(area => {
                if(area.name === "main")
                {
                    //Indlæser komponenterne i layout området
                    this.mainComponents = area.components;
                }
                else if(area.name === "left")
                {
                    //Indlæser komponenterne i layout området
                    this.leftComponents = area.components;
                }
            });
        },
        methods: {
            emitComponentEvent: function(event, area)
            {
                //Updater layout områderne med det opdaterede komponent
                if(area === "main")
                {
                    for(let i = 0; i < this.mainComponents.length; i++)
                    {
                        if(this.mainComponents[i].id === event.id && this.mainComponents[i].componentType === event.componentType)
                        {
                            this.mainComponents[i] = event;
                        }
                        else if(this.mainComponents[i].componentType === "")
                        {
                            this.mainComponents[i] = event;
                            break;
                        }
                    }
                }
                else if(area === "left")
                {
                    for(let i = 0; i < this.leftComponents.length; i++)
                    {
                        if(this.leftComponents[i].id === event.id && this.leftComponents[i].componentType === event.componentType)
                        {
                            this.leftComponents[i] = event;
                        }
                        else if(this.leftComponents[i].componentType === "")
                        {
                            this.leftComponents[i] = event;
                            break;
                        }
                    }
                }

                let layoutEvent = {
                    layoutType: "LeftSplittedLayout",
                    layoutAreas: [
                        {
                            name: "main",
                            components: this.mainComponents,
                        },
                        {
                            name: "left",
                            components: this.leftComponents,
                        }
                    ],
                };
                //Send update eventet videre til admin applikationen
                this.$emit("update:component", layoutEvent);
            },
            emitEvent: function(event)
            {
                //Send update event med en ny rute der skal vises
                this.$emit('update:route', event)
            },
            addMainComponent: function(){
                //Opretter et nyt komponent uden type i det højre område
                let component = {
                    componentType: ""
                };
                this.mainComponents.push(component);
            },
            addLeftComponent: function(){
                //Opretter et nyt komponent uden type i det venstre område
                let component = {
                    componentType: ""
                };
                this.leftComponents.push(component);
            }
        }
    }
</script>

<style scoped>
</style>