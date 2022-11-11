<template>
    <v-card
    class="mx-auto"
    max-width="600"
    >

    <v-card-title>{{ title }}</v-card-title>

    <v-card-subtitle>Vidéo | {{ date }}</v-card-subtitle>

    <v-divider></v-divider>

    <v-card-text>
      <div class="text-center">
          <video preload="none" controls="" width="300" :src="source" :poster="thumbnails"></video>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    props: ['title','source','thumbnails','date'],
  }
</script>