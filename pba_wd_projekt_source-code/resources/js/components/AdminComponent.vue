<template>
    <v-container fluid style="padding:0px;">
        <v-toolbar>
            <v-toolbar-items>
                <v-select height="64" v-on:change="setLayout" v-model="selectedLayout" :items="layoutTypes" label="Choose layout type here"></v-select>
            </v-toolbar-items>
            <v-toolbar-items>
                <v-text-field height="64" v-model="loadedLayoutName" label="Input name"></v-text-field>
                <v-text-field height="64" v-model="loadedLayoutRoute" label="Input page"></v-text-field>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-select height="64" v-on:change="loadLayout" v-model="selectedRoute" :items="layoutRoutes" label="Open layout"></v-select>
            </v-toolbar-items>
            <v-toolbar-items>
                <v-btn color="green darken-1" flat dark v-on:click="saveRoute">Save layout</v-btn>
                <v-btn color="blue darken-1" flat dark href="../admin">Dashboard</v-btn>
                <v-menu>
                    <template v-slot:activator="{ on }">
                        <v-btn
                        color="blue-grey darken-1"
                        dark
                        flat
                        v-on="on">
                            <v-icon>
                                fa fa-user-circle-o
                            </v-icon>
                             Hello {{ adminUser }}
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item
                        v-for="(item, index) in adminMenu"
                        :key="index"
                        @click="goToLink(item)">
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-toolbar-items>
        </v-toolbar>
        <single-area-layout 
            v-on:update:component="emitComponentEvent($event)" 
            v-if="this.selectedLayout === 'Single Area Layout'" 
            v-bind:isAdmin="this.isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:layoutAreas="layoutAreas"
            :key="updateSingle"></single-area-layout>
        <left-splitted-layout 
            v-on:update:component="emitComponentEvent($event)" 
            v-else-if="this.selectedLayout === 'Left Splitted Layout'" 
            v-bind:isAdmin="this.isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:layoutAreas="layoutAreas"
            :key="updateSplitted"></left-splitted-layout>
    </v-container>
</template>

<script>
    export default {
        data: function(){
            return {
                adminUser: "",
                adminMenu: [
                    { title: "Dashboard", link: "./../"},
                    { title: "Log out", link: "./../logout"}
                ],
                layoutTypes: [
                    "Single Area Layout",
                    "Left Splitted Layout"
                ],
                selectedLayout: "",
                selectedRoute: "",
                isAdmin: true,
                layoutAreas: [],
                adminImages: [],
                layouts: [],
                layoutRoutes: [],
                responseData: "",
                newLayoutName: "",
                loadedLayoutName: "",
                loadedLayoutRoute: "",
                route: "",
                name: "",
                updateSingle: 0,
                updateSplitted: 0,
            }
        },
        mounted() {           
            //Henter brugernavnet fra html meta tag         
            this.adminUser = window.username.content; 
            let url = '../api/home';
            //Henter et layout for en rute fra PHP
            axios.get(url).then(resp => {
                this.responseData = resp.data;
                this.layouts = this.responseData.layouts;
                this.layouts.forEach(l => {
                    this.layoutRoutes.push(l.name);
                });
            });
            url = '../api/images';
            //Henter billederne der er uploaded fra PHP
            axios.get(url).then(resp => {
                this.responseData = resp.data;
                this.responseData.forEach(i => {
                    let path = i.path.split(3);
                    this.adminImages.push("storage/images/" + path);
                });
            });
        },
        methods: {
            emitComponentEvent: function(event)
            {
                //Opdaterer layoutet når der opdateres et komponent eller layout type
                this.layoutType = event.layoutType;
                this.layoutAreas = event.layoutAreas;
            },
            setLayout: function(){
                this.layoutAreas = [];
                //Sætter den korrekte layout type
                if(this.selectedLayout === "Single Area Layout")
                {
                    let mainArea = {
                        name: "main",
                        components: [],
                    };
                    this.layoutAreas.push(mainArea);
                }
                else if(this.selectedLayout === "Left Splitted Layout")
                {
                    let mainArea = {
                        name: "main",
                        components: [],
                    };
                    this.layoutAreas.push(mainArea);

                    let leftArea = {
                        name: "left",
                        components: [],
                    };
                    this.layoutAreas.push(leftArea);
                }
            },
            loadLayout: function(){
                //Viser et gemt layout samt alle komponenterne
                if(this.selectedRoute !== "undefined" && this.selectedRoute !== "")
                {
                    let endPoint = "";

                    this.layouts.forEach(l => {
                        if(this.selectedRoute === l.name)
                        {
                            endPoint = l.route;
                            this.loadedLayoutName = l.name;
                            //Fjerner / foran ruten inden det sendes til PHP
                            this.loadedLayoutRoute = l.route.substr(1);
                        }
                    });
                    
                    let url = '../api' + endPoint;
                    //Henter layoutet for ruten fra PHP
                    axios.get(url).then(resp => {
                        this.responseData = resp.data;

                        this.layoutAreas = [];
                
                        if(this.responseData.layoutType === "SingleArea" || this.responseData.layoutType === "LeftSplitted")
                        {
                            this.responseData.layoutAreas.forEach(a => {
                                let area = {
                                    name: a.name,
                                    components: a.components,
                                };
                                this.layoutAreas.push(area);

                                if(this.responseData.layoutType === "SingleArea")
                                {
                                    this.selectedLayout = "Single Area Layout";
                                    this.updateSingle++;
                                }
                                else if(this.responseData.layoutType === "LeftSplitted")
                                {
                                    this.selectedLayout = "Left Splitted Layout";
                                    this.updateSplitted++;
                                }
                            });
                        }
                    });
                }
            },
            saveRoute: function()
            {
                let layoutType = "";

                if(this.selectedLayout === "Left Splitted Layout")
                {
                    layoutType = "LeftSplitted";
                }
                else if(this.selectedLayout === "Single Area Layout")
                {
                    layoutType = "SingleArea";
                }
                
                this.route = this.loadedLayoutRoute;
                this.name = this.loadedLayoutName;

                let json = {
                    layoutName: this.name,
                    route: this.route,
                    layoutType: layoutType,
                    layoutAreas: this.layoutAreas,
                };

                let url = '../api/edit';
                //Sender det nye/ændrede layout til PHP
                axios.post(url, json).then(resp => {
                    this.layoutAreas = resp.data.layoutAreas;
                    location.reload(); 
                });
            },
            goToLink: function(item)
            {
                if(item.link !== "")
                {
                    let url = window.location.pathname + item.link;

                    if(item.title === "Log out")
                    {
                        //Logger brugeren ud
                        axios.post(item.link).then(resp => {
                            window.location.href = "./";
                        });
                    }
                    else
                    {
                        location.href = url;
                    }
                }
            }
        }
    }
</script>