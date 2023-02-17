<template>
  <v-app>
    <v-app-bar
      app
      color="red"
      dark
      :prominent="!!mobile"
    >
      <v-container class="py-0 fill-height">
        <v-icon
          class="mr-10"
          large
        >
          mdi-video-box
        </v-icon>

        <v-btn
          text
          href="https://apps.arfevrier.fr/youtube_player/"
        >
        {{ title }}
        </v-btn>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-btn 
            text
            href="https://api.arfevrier.fr/v2/youtube/login"
        >
          Abonnements
        </v-btn>
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

    <v-navigation-drawer
      v-model="commentaire"
      absolute
      bottom
      temporary
      right
      width="50%"
      color="grey lighten-5"
    >
    <div v-if="chargementCommentaire" class="text-center my-12">
          <v-progress-circular
              :size="70"
              :width="7"
              color="red"
              indeterminate
          >
          </v-progress-circular>
    </div>
    <v-container>
          <v-row>
            <v-col v-for="(object, index) in comments" :key="index" :cols="6">
              <CommentCard :user="object.user" :index="index+1" :message="object.message" :date="dateToString(object.date)" :color="object.color"/>
            </v-col>
          </v-row>
      </v-container>
    </v-navigation-drawer>

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
            <v-col v-for="(object, index) in videos" :key="'V'+index">
              <VideoCard :title="object.title" :id="object.id" :source="object.source" @showComment="comment($event)"/>
            </v-col>
            <v-col v-for="(object, index) in audios" :key="'A'+index">
              <AudioCard :title="object.title" :id="object.id" :source="object.source"/>
            </v-col>
            <v-col v-for="(object, index) in subscriptions" :key="index">
              <SubscriptionCard :title="object.title" :source="'https://api.arfevrier.fr/v2/youtube/video/'+object.resourceId.videoId" :id="object.resourceId.videoId" :thumbnails="object.thumbnails.medium.url" :date="dateToString(object.publishedAt)" @startSearch="search($event)"/>
            </v-col>
          </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
  import CommentCard from './CommentCard';
  import VideoCard from './VideoCard';
  import AudioCard from './AudioCard';
  import SubscriptionCard from './SubscriptionCard';
  import functions from '../plugins/functions';

  export default {
    components: {
        CommentCard,
        VideoCard,
        AudioCard,
        SubscriptionCard
    },
    computed: {
      mobile() {
        return this.$vuetify.breakpoint.sm || this.$vuetify.breakpoint.xs
      },
      title() {
        if (this.$vuetify.breakpoint.sm || this.$vuetify.breakpoint.xs){
          return 'Vidéo & audio YouTube'
        } else {
          return 'Lecteur vidéo et audio YouTube'
        }
      }
    },
    data: () => ({
      chargementCommentaire: false,
      commentaire: false,
      chargement: false,
      url: '',
      erreur: false,
      subscriptions: [],
      videos: [],
      audios: [],
      comments: [],
    }),
    mounted() {
      this.startup()
    },
    methods: {
        startup(){
            if (window.location.hash.includes("#subscriptions")){
              this.$data.chargement = true
              fetch("https://api.arfevrier.fr/v2/youtube/subscriptions/"+window.location.hash.split("=")[1])
              .then(response => {
                  if(response.ok){
                      return response.json()
                  } else {
                      throw '!= 200';
                  }         
              })
              .then(response => {
                  this.$data.subscriptions = response.subscriptions
              })
              .catch(err => {
                  console.log(err);
                  this.$data.erreur = true;
              })
              .finally(() =>{
                  this.$data.chargement = false
              });
            }
            if (window.location.search.includes("?q=")){
              this.$data.url = decodeURIComponent(window.location.search.split("=")[1])
              this.generate()
            }
        },
        generate(){
            this.$data.chargement = true
            var yt_id = functions.youtubeGetID(this.$data.url)
            this.$data.url = ''

            Promise.all([
              fetch("https://www.googleapis.com/youtube/v3/videos?id="+yt_id+"&part=snippet&key=AIzaSyDvXwykt34G-Ebxa1kNyDCqAuAo0Jj6J5k")
            ]).then(([title]) => {
                if(title.ok){
                    return Promise.all([title.json()])
                } else {
                    throw '!= 200';
                }         
            })
            .then(([title]) => {
                this.$data.videos.push({title:title.items[0].snippet.title, id:yt_id, source:"https://api.arfevrier.fr/v2/youtube/video/"+yt_id})
                this.$data.audios.push({title:title.items[0].snippet.title, id:yt_id, source:"https://api.arfevrier.fr/v2/youtube/audio/"+yt_id})
            })
            .catch(err => {
                console.log(err);
                this.$data.erreur = true;
            })
            .finally(() =>{
                this.$data.chargement = false
            });
        },
        dateToString(date){
            return new Date(date).toLocaleString()
        },
        async requestComments(id, pageToken=''){
          this.$data.chargementCommentaire = true
          var response = await fetch("https://www.googleapis.com/youtube/v3/commentThreads?order=time&maxResults=100&videoId="+id+"&part=snippet&key=AIzaSyDvXwykt34G-Ebxa1kNyDCqAuAo0Jj6J5k&pageToken="+pageToken)
          response = await response.json()
          response.items.forEach(element => {
            this.$data.comments.push({user:element.snippet.topLevelComment.snippet.authorDisplayName,
                                      message:element.snippet.topLevelComment.snippet.textOriginal,
                                      date:element.snippet.topLevelComment.snippet.publishedAt,
                                      color:functions.getRandomColor()
                                    })
          })
          if(response.nextPageToken != undefined){
						this.requestComments(id, response.nextPageToken)
					} else {
            this.$data.chargementCommentaire = false
          }
        },
        comment(id){
          this.$data.comments.splice(0)
          this.$data.commentaire = !this.$data.commentaire
          this.requestComments(id)
        },
        search(id){
          this.$data.url = id
          this.generate()
        }
    },
  }
</script>