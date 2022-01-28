<template>
  <v-app>
    <v-app-bar
      app
      color="red"
      dark
      :prominent="!!mobile"
    >
      <v-container class="py-0 fill-height">
        <v-avatar
          class="mr-10"
          size="32"
        >
          <img src="../assets/youtube.png">
        </v-avatar>

        <v-btn
          v-for="link in links"
          :key="link"
          text
        >
          {{ link }}
        </v-btn>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-btn 
            text
            @click="generate"
        >
          Lancer
        </v-btn>

        
          <v-text-field
            dense
            flat
            hide-details
            rounded
            solo-inverted label="ID/URL de la vidéo"
            v-model="url"
            @keydown.enter="generate"
          ></v-text-field>
        
      </v-container>
    </v-app-bar>

    <v-main>
      <div v-if="chargement" class="text-center my-12">
          <v-progress-circular
              :size="70"
              :width="7"
              color="red"
              indeterminate
          >
          </v-progress-circular>
      </div>

      <v-container class="d-flex">
        <v-alert v-model="erreur" type="error" dismissible class="mx-auto my-4 text-center">
          ID/URL Incorrect
        </v-alert>
      </v-container>

      <v-container>
          <v-row>
            <v-col v-for="(object, index) in videos" :key="index">
              <VideoCard :title="object.title" :source="object.source"/>
            </v-col>
            <v-col v-for="(object, index) in audios" :key="index">
              <AudioCard :title="object.title" :source="object.source"/>
            </v-col>
          </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
  import VideoCard from './VideoCard';
  import AudioCard from './AudioCard';
  import functions from '../plugins/functions';

  export default {
    components: {
        VideoCard,
        AudioCard
    },
    computed: {
      mobile() {
        return this.$vuetify.breakpoint.sm || this.$vuetify.breakpoint.xs
      },
    },
    data: () => ({
      chargement: false,
      url: '',
      erreur: false,
      links: [
        'Lecteur vidéo et audio YouTube'
      ],
      videos: [],
      audios: [],
    }),
    methods: {
        generate(){
            this.$data.chargement = true
            var yt_id = functions.youtubeGetID(this.$data.url)
            this.$data.url = ''
            
            //Video part
            fetch("https://api.arfevrier.fr/v2/youtube/video/"+yt_id+"?url")
            .then(response => {
                if(response.ok){
                    return response.json()
                } else {
                    throw '!= 200';
                }         
            })
            .then(response => {
                this.$data.videos.push({title:yt_id, source:response})
            })
            .catch(err => {
                console.log(err);
                this.$data.erreur = true;
            })
            .finally(() =>{
                this.$data.chargement = false
            });

            //Audio part
            fetch("https://api.arfevrier.fr/v2/youtube/audio/"+yt_id+"?url")
            .then(response => {
                if(response.ok){
                    return response.json()
                } else {
                    throw '!= 200';
                }         
            })
            .then(response => {
                this.$data.audios.push({title:yt_id, source:response})
            })
            .catch(err => {
                console.log(err);
                this.$data.erreur = true;
            })
            .finally(() =>{
                this.$data.chargement = false
            });
        }
    },
  }
</script>