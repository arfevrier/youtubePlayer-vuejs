<template>
  <v-card
  class="mx-auto"
  max-width="600"
  >
    <v-card-item>
      <v-card-title>{{ title }}</v-card-title>
      <v-card-subtitle>{{ channel }} | {{ date }}</v-card-subtitle>
    </v-card-item>

    <v-divider></v-divider>

    <v-card-text>
      <div class="text-center">
          <video preload="none" controls="" width="300" :src="source" :poster="thumbnails"></video>
      </div>
    </v-card-text>
    <v-card-actions style="margin-top: -25px;">
              <v-spacer></v-spacer>
              <v-btn icon @click.stop="$emit('startSearch', id)">
                <v-icon color="red">mdi-magnify</v-icon>
              </v-btn>
      </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    props: ['title','source','thumbnails','date','id','channel'],
  }
</script>