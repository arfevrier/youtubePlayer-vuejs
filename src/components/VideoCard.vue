<template>
  <v-card
  class="mx-auto"
  max-width="600"
  >
    <v-card-item>
      <v-card-title>{{ title }}</v-card-title>
      <v-card-subtitle>{{ id }}</v-card-subtitle>
    </v-card-item>

    <v-divider></v-divider>

    <v-card-text>
      <div class="text-center">
          <video controls="" width="300" :src="source"></video>
      </div>
    </v-card-text>
    <v-card-actions class="pt-0">
      <v-btn icon :loading="loading_video" @click="load_video" :href="`${stc.afr.v4}/youtube/download/video/${id}`">
        <v-icon color="red">mdi-download-box</v-icon>
      </v-btn>
      <v-btn icon :loading="loading_audio" @click="load_audio" :href="`${stc.afr.v4}/youtube/download/audio/${id}`" style="margin-left: 0;">
        <v-icon color="red">mdi-download-box-outline</v-icon>
      </v-btn>
      <v-spacer></v-spacer>
      <v-btn icon @click.stop="$emit('showComment', id)">
        <v-icon color="red">mdi-message-text-fast</v-icon>
      </v-btn>
      </v-card-actions>
  </v-card>
</template>

<script>
  import stc from '../plugins/static';
  export default {
    props: ['title','source','id'],
    data: () => ({
      stc: stc,
      loading_video: false,
      loading_audio: false,
    }),
    methods: {
      load_video() {
        this.loading_video = true
        setTimeout(() => (this.loading_video = false), 10000)
      },
      load_audio() {
        this.loading_audio = true
        setTimeout(() => (this.loading_audio = false), 10000)
      },
    },
  }
</script>