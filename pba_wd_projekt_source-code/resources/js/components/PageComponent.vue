<template>
    <v-container fluid style="padding:0px;">
        <single-area-layout 
            v-on:update:route="updatedRoute($event)" 
            v-if="this.layoutType === 'SingleArea'" 
            v-bind:isAdmin="this.isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:layoutAreas="this.layoutAreas"
            :key="updateSingle"></single-area-layout>
        <left-splitted-layout 
            v-on:update:route="updatedRoute($event)" 
            v-else-if="this.layoutType === 'LeftSplitted'" 
            v-bind:isAdmin="this.isAdmin" 
            v-bind:adminImages="adminImages" 
            v-bind:layoutAreas="this.layoutAreas"
            :key="updateSplitted"></left-splitted-layout>
    </v-container>
</template>

<script>
    export default {
        data: function(){
            return {
                responseData: '',
                layoutType: '',
                layoutAreas: '',
                isAdmin: false,
                adminImages: [],
                updateSingle: 0,
                updateSplitted: 0,
            }
        },
        mounted() {
            let urlPathArray = window.location.pathname.split('/');
            let route = urlPathArray[urlPathArray.length - 1];

            if(route === "")
            {
                route = "home";
            }
            
            let url = 'api/' + route;
            //Henter et layout for en rute fra PHP
            axios.get(url).then(resp => {
                this.responseData = resp.data;
                this.layoutType = this.responseData.layoutType;
                this.layoutAreas = this.responseData.layoutAreas;
            });
        },
        methods: {
            updatedRoute: function(event)
            {
                //Skifter ruten når et Navigations komponent bliver klikket på
                let route = event.route;

                if(route === "")
                {
                    route = "home";
                }
                
                let url = 'api/' + route;
                //Henter layoutet for en rute der klikkes på fra PHP
                axios.get(url).then(resp => {
                    this.updateSingle++;
                    this.updateSplitted++;
                    this.responseData = resp.data;                    
                    this.layoutType = this.responseData.layoutType;
                    this.layoutAreas = this.responseData.layoutAreas;
                });
            },
        },
    }
</script>
