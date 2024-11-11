<template>
  <v-app>
    <v-app-bar
      app
      :absolute="absoluteAppbar"
      color="red"
      dark
      :density="sizeAppbar"
    >
      <v-container class="py-0 fill-height">
        <v-icon
          class="mr-10"
          size="x-large"
        >
          mdi-video-box
        </v-icon>

        <v-btn
          text
          href="./"
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

        <v-text-field
          :loading="chargement"
          hide-details
          density="default"
          label="ID/URL de la vidéo"
          append-inner-icon="mdi-magnify"
          v-model="url"
          @keydown.enter="generate"
          @click:append-inner="generate"
        ></v-text-field>       
      </v-container>
    </v-app-bar>

    <v-navigation-drawer
      v-model="commentaire"    
      temporary
      width="750"
      color="#fafafa"
    >
    <v-spacer></v-spacer>
    <div class="text-right">    
    <v-btn @click="commentaire = false" variant="text" icon="mdi-close"></v-btn>
    </div>
    <div v-if="chargementCommentaire" class="text-center my-12">
          <v-progress-circular
              :size="70"
              :width="7"
              color="red"
              indeterminate
          >
          </v-progress-circular>
    </div>
    <v-container class="pt-0">
          <v-row>
            <v-col v-for="(object, index) in comments" :key="index" :cols="6">
              <CommentCard :user="object.user"
                           :index="index+1"
                           :message="object.message"
                           :date="dateToString(object.date)"
                           :color="object.color" />
            </v-col>
          </v-row>
      </v-container>
    </v-navigation-drawer>

    <v-main>
      <!-- <div v-if="chargement" class="text-center my-12">
          <v-progress-circular
              :size="70"
              :width="7"
              color="red"
              indeterminate
          >
          </v-progress-circular>
      </div> -->

      <v-container class="d-flex">
        <v-alert 
          v-model="erreur" 
          type="warning" 
          color="red" 
          closable 
          class="mx-auto my-4 text-center" 
          style="max-width: 300px">
            ID/URL Incorrecte
        </v-alert>
      </v-container>

      <v-container>
          <v-row>
            <v-col v-for="(object, index) in videos" :key="'V'+index">
              <VideoCard :title="object.title"
                         :id="object.id"
                         :source="`${stc.afr.v2}/youtube/video/${object.id}`"
                         @showComment="comment($event)" />
            </v-col>
            <v-col v-for="(object, index) in audios" :key="'A'+index">
              <AudioCard :title="object.title"
                         :id="object.id"
                         :source="`${stc.afr.v2}/youtube/audio/${object.id}`" />
            </v-col>
            <v-col v-for="(object, index) in subscriptions" :key="index">
              <SubscriptionCard :title="object.video_title"
                                :source="`${stc.afr.v2}/youtube/video/${object.video_id}`"
                                :id="object.video_id"
                                :channel="object.author_name"
                                :thumbnails="`${stc.yt.thumbnail}/${object.video_id}/mqdefault.jpg`"
                                :date="dateToString(object.published_date)"
                                @startSearch="search($event)" />
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
  import stc from '../plugins/static';

  export default {
    components: {
        CommentCard,
        VideoCard,
        AudioCard,
        SubscriptionCard
    },
    computed: {
      sizeAppbar() {
        return this.$vuetify.display.mobile ? 'prominent' : 'default'
      },
      title() {
        return this.$vuetify.display.mobile ? 'Vidéo & audio YouTube' : 'Lecteur vidéo et audio YouTube'
      },
      absoluteAppbar() {
        return this.$vuetify.display.mobile ? true : false
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
      stc: stc,
    }),
    mounted() {
      this.startup()
    },
    methods: {
        startup(){
            if (window.location.hash.includes("#subscriptions")){
              this.$data.chargement = true
              fetch(`${stc.afr.v3}/youtube/subscriptions/${window.location.hash.split("=")[1]}`)
              .then(response => {
                  if(response.ok){
                      return response.json()
                  } else {
                      throw '!= 200';
                  }         
              })
              .then(response => {
                  response.subscriptions.sort(function(a,b){
                    return new Date(b.published_date) - new Date(a.published_date);
                  }).splice(25);
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
            var yt_id = functions.youtubeGetID(this.$data.url)
            this.$data.erreur = false
            this.$data.chargement = true
            this.$data.url = ''

            Promise.all([
              fetch(`${stc.yt.api}/videos?id=${yt_id}&part=snippet&key=${stc.yt.key}`)
            ]).then(([title]) => {
                if(title.ok){
                    return Promise.all([title.json()])
                } else {
                    throw '!= 200';
                }         
            })
            .then(([title]) => {
                this.$data.videos.push({title:title.items[0].snippet.title, id:yt_id})
                this.$data.audios.push({title:title.items[0].snippet.title, id:yt_id})
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
          var response = await fetch(`${stc.yt.api}/commentThreads?order=time&`
                                                            + `maxResults=100&`
                                                            + `videoId=${id}&`
                                                            + `part=snippet&`
                                                            + `key=${stc.yt.key}&`
                                                            + `pageToken=${pageToken}`)
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