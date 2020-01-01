<template>
    <v-container fluid style="height:100%;">
        <v-toolbar>
            <v-toolbar-items class="d-flex align-center">
                <img style="width: 64px;height: auto;" v-bind:src="siteLogo" v-bind:alt="siteTitle">
                <v-toolbar-title>{{ siteOwner }}</v-toolbar-title>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn 
                    color="green darken-1"
                    dark
                    flat
                    href="admin/edit">
                        Page Editor
                </v-btn>
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
        <v-row style="height:100%;">
            <v-col cols="2">
                <v-navigation-drawer style="padding:8px!important;">
                    <v-row>
                        <v-col cols="12">
                            <v-btn 
                                block
                                text
                                @click="showSettings = !showSettings">
                                Settings
                            </v-btn>
                        </v-col>
                        <v-divider></v-divider>
                        <v-col cols="12">
                            <v-btn
                                block 
                                text
                                @click="showImages = !showImages">
                                Images
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-navigation-drawer>
            </v-col>
            <v-col cols="10">
                <v-row>
                    <v-col cols="5">
                        <v-row>
                            <v-col cols="12">
                                <div v-show="showSettings">
                                    <v-card>
                                        <v-card-title>
                                            <h1>Settings</h1>
                                        </v-card-title>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field v-model="siteTitle" label="Title"></v-text-field>
                                                <v-text-field v-model="siteOwner" label="Owner"></v-text-field>
                                                <v-select v-model="selectedLogo" :items="adminImages" label="Choose image"></v-select>
                                            </v-col>
                                        </v-row>
                                        <v-btn
                                            color="teal darken-1"
                                            dark
                                            flat
                                            block
                                            v-on:click="submitSettingsForm">
                                            Update
                                        </v-btn>
                                    </v-card>
                                </div>
                                <div v-show="showImages">
                                    <v-card>
                                        <v-card-title>
                                            <h1>Upload image</h1>
                                        </v-card-title>
                                        <v-row>
                                            <v-col cols="12" class="d-flex justify-center">
                                                <input type="file" id="file" ref="file" v-on:change="onChangeFileUpload()"/>
                                            </v-col>
                                        </v-row>
                                        <v-btn
                                            color="teal darken-1"
                                            dark
                                            flat
                                            block
                                            v-on:click="submitImageForm">
                                            Upload
                                        </v-btn>
                                    </v-card>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="5">
                        <v-card>
                            <v-card-title class="d-flex justify-center">
                                <h1>{{ siteTitle }}</h1>
                            </v-card-title>
                        <v-row>
                            <v-col cols="12" class="d-flex justify-center">
                                <strong>
                                    Made on Laravel, Vue and Vueitify...
                                </strong>
                            </v-col>
                            <v-col cols="12" class="d-flex justify-center">
                                <v-btn 
                                    color="green darken-1"
                                    dark
                                    href="admin/edit">
                                    Go to Page Editor
                                </v-btn>
                            </v-col>
                        </v-row>
                        </v-card>
                    </v-col>
                    <v-col cols="2">

                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data: function(){
            return {
                file: "",
                showImages: false,
                showSettings: false,
                adminUser: "",
                adminMenu: [
                    { title: "Page Editor", link: "/edit"},
                    { title: "Log out", link: "./logout"}
                ],
                siteTitle: "",
                siteLogo: "",
                siteOwner: "",
                adminImages: [],
                selectedLogo: "",
            }
        },
        mounted() {   
            //Henter brugernavnet fra html meta tag         
            this.adminUser = window.username.content;
            //Henter side titlen fra html meta tag 
            this.siteTitle = window.siteTitle.content;
            //Henter side logoet fra html meta tag 
            this.selectedLogo = window.siteLogo.content;
            this.siteLogo = "./storage/images/" + this.selectedLogo;
            //Henter side ejeren fra html meta tag 
            this.siteOwner = window.siteOwner.content;
            let url = './api/images';
            //Henter billederne der er uploaded fra PHP
            axios.get(url).then(resp => {
                this.responseData = resp.data;
                this.responseData.forEach(i => {
                    this.adminImages.push(i.path);
                });
            });
        },
        methods: {
            submitSettingsForm: function()
            {
                if(this.siteTitle === "undefined")
                {
                    this.siteTitle = "";
                }

                if(this.siteLogo === "undefined")
                {
                    this.siteLogo = "";
                }

                if(this.siteOwner === "undefined")
                {
                    this.siteOwner = "";
                }

                let formData = new FormData();
                formData.append('siteTitle', this.siteTitle);
                formData.append('siteLogo', this.selectedLogo);
                formData.append('siteOwner', this.siteOwner);

                //Opdaterer side indstillingerne
                axios.post("./api/settings", formData).then(resp => {
                    location.reload();
                });
            },
            submitImageForm: function()
            {
                //Uploader et billede
                let formData = new FormData();
                formData.append('file', this.file, this.file.name);

                axios.post("./api/image", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(resp => {
                    location.reload();
                });
            },    
            onChangeFileUpload: function()
            {
                //Gemmer fil informationen fra formen i this.file
                this.file = this.$refs.file.files[0];
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
                            window.location.href = "./admin";
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